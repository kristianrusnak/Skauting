import openai
import json
import mysql.connector
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity

class Similarities:

    def evaluate(self, vector, all_vectors):
        return cosine_similarity(vector, all_vectors)[0]

class OpenAiEmbedding(Similarities):

    def __init__(self):
        self._mydb = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="skaut"
        )

        self._myCursor = self._mydb.cursor()

        # 🔹 Set OpenAI API key
        with open("config.json") as f:
            config = json.load(f)

        openai.api_key = config["openai_api_key"]

    def is_table_for_model_created(self, model):
        self._myCursor.execute(f"SHOW TABLES LIKE `{model}`")
        result = self._myCursor.fetchall()
        if result:
            return True
        return False

    def create_table_for_model(self, model):
        if self.is_table_for_model_created(model):
            return

        query = f"""
                CREATE TABLE `{model}` (
                    task_id INT NOT NULL,
                    vectors JSON NOT NULL,
                    CONSTRAINT fk_task FOREIGN KEY (task_id) REFERENCES skaut.tasks(id) ON DELETE CASCADE ON UPDATE CASCADE
                );
                """

        self._myCursor.execute(query)
        self._mydb.commit()

    def get_all_embeddings(self):
        self._myCursor.execute(f"SELECT * FROM `text-embedding-3-large`")
        return [(x[0], json.loads(x[1])) for x in self._myCursor.fetchall()]

    def get_embedding(self, id):
        self._myCursor.execute(f"SELECT vectors FROM `text-embedding-3-large` WHERE task_id = {id}")
        embedding =  self._myCursor.fetchone()
        if embedding is None:
            return []
        return json.loads(embedding[0])

    def embedd_all_tasks(self):

        self._myCursor.execute("SELECT id, task FROM tasks")
        tasks = self._myCursor.fetchall()

        for task in tasks:
            self.embedd_task(task[0], task[1])

    def embedd_task(self, id, text, model="text-embedding-3-large"):
        cached = self.get_embedding(id)
        if cached:
            return cached

        # Call OpenAI if not cached
        response = openai.embeddings.create(input=text, model=model)
        embedding = response.data[0].embedding

        # Save to cache and update file
        json_data = json.dumps(embedding)
        query = "INSERT INTO `text-embedding-3-large` (task_id, vectors) VALUES (%s, %s)"
        self._myCursor.execute(query, (id, json_data))
        self._mydb.commit()

        return embedding

    def add_match_task_to_table(self, task_id, match_task_id):
        self._myCursor.execute(f"INSERT INTO matched_tasks (task_id, match_task_id) VALUES ({task_id}, {match_task_id})")
        self._mydb.commit()

    def match_all_tasks(self):
        self.embedd_all_tasks()

        self._myCursor.execute(f"SELECT id FROM tasks")
        tasks = self._myCursor.fetchall()

        for task_id in tasks:
            self.match_task(task_id[0])

    def match_task(self, id):
        embeddings = self.get_all_embeddings()
        all_vectors = np.array([x[1] for x in embeddings])
        vector = np.array([self.get_embedding(id)])

        similarities = self.evaluate(vector, all_vectors)

        # 🔹 Get top 3 similar tasks
        top_indices = np.argsort(similarities)[::-1][1:]

        counter = 0
        for idx in top_indices:
            if counter >= 10:
                break
            if not self.are_task_from_same_group(id, embeddings[idx][0]):
                counter += 1
                self.add_match_task_to_table(id, embeddings[idx][0])

        # 🔹 Print results - useful for debugging
        # print(f"\n🔍 Podobné úlohy k: '{id}'\n")
        # for idx in top_indices:
        #     print(f"✅ {embeddings[idx][0]} (Podobnosť: {similarities[idx]:.2f})")

    def are_task_from_same_group(self, task_id, match_task_id):
        merit_badge = self.is_task_from_merit_badge(task_id)
        matched_merit_badge = self.is_task_from_merit_badge(match_task_id)

        if merit_badge:
            if merit_badge == matched_merit_badge:
                return True
            return False
        else:
            if matched_merit_badge:
                return False
            if self.is_task_from_scout_path(task_id) == self.is_task_from_scout_path(match_task_id):
                return True
            return False

    def is_task_from_merit_badge(self, task_id):
        self._myCursor.execute(f"SELECT merit_badge_id FROM merit_badge_tasks WHERE task_id = {task_id}")
        result = self._myCursor.fetchone()

        if result is None:
            return 0
        return result[0]

    def is_task_from_scout_path(self, task_id):
        self._myCursor.execute(f"SELECT chapter_id FROM scout_path_tasks WHERE task_id = {task_id}")
        result = self._myCursor.fetchone()

        if result is None:
            return 0
        return result[0]