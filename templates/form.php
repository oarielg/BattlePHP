<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <p>What are you going to do?</p>
    <p><input type="submit" name="attack" value="Attack"></p>
    <p><select name="spell_id">
    <?php
        foreach($player['spells'] as $spell_id)
        {
            $spell = get_spell($spell_id);
            echo "<option value=\"".$spell_id."\">".$spell['name']."</option>";
        }   
    ?>    
    </select>
    <input type="submit" name="spell" value="Cast Spell"></p>
    <p><input type="submit" name="run" value="Run"></p>
</form>