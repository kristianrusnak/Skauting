#!/usr/bin/env python3

import OpenAiEmbedding
import sys

task_id = sys.argv[1]  # task id
text = sys.argv[2]   # task

ai = OpenAiEmbedding.OpenAiEmbedding()
ai.embedd_task(task_id, text)
ai.match_task(task_id)

# debug
# ai = OpenAiEmbedding.OpenAiEmbedding()
# ai.embedd_task(1747, "Toto je skuska 1")
# ai.match_task(1747)