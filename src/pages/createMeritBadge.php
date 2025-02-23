<?php

echo '
    <form method="POST">
        <label>
            <span>Meno odborky: </span>
            <input type="text" name="MeritBadgeName" required>
        </label>
        
        <br>
        
        <label>
            <span>Kategoria odborky: </span>
            <select name="MeritBadgeCategory" required>
                <option value="1">Priroda</option>
                <option value="2">Sport</option>
            </select>
        </label>
        
        <br>
        
        <label>
            <span>Farba odborky: </span>
            <input type="color" name="MeritBadgeColor" required>
        </label>
        
        <br>
        
        <label>
            <span>Zelený stupeň: </span>
            <input type="file" name="MeritBadgeImageG">
        </label>
        
        <br>
        
        <label>
            <span>Červený stupeň: </span>
            <input type="file" name="MeritBadgeImageR">
        </label>
        
        <br>
        
        <input type="submit" name="createMeritBadge" value="Vytvorit Odborku">
    </form>
';

?>