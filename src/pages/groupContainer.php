<?php
echo '
<!-- Main container-->
<div id="groupContainer">
    <!-- Group Container -->
    <div class="taskCheckerContainer">
        <!-- Main group checkbox that selects all tasks in the group-->
        <div class="headOfTheGroup">
            <span class="selectAllInTheGroupSpan">Druzina: Adam Bednar</span>
        </div>
        
         <!-- Container with each member and its tasks -->
        <div class="membersOfTheGroup" style="display: none">
            <div class="memberOfTheGroup">
                <!-- Checkbox for selecting all tasks of the member -->
                <div class="selectAllFromTheMember" style="background-color: #fdd4b1">
                    <span class="selectAllInTheGroupSpan">Adam Bednar</span>
                </div>
                <!-- each task of the member -->
                <form>
                    <div class="groupContainerTasks" style="border: 2px dashed #fdd4b1">
                        <div class="groupContainerTask">
                            <label>
                                <span class="groupContainerTaskSpan">Pozicia: </span>
                                <select id="pozicia">
                                    <option value="1">Skaut</option>
                                    <option value="2" style="display: none">Rodic</option>
                                    <option value="3" selected style="font-weight: bolder">Radca</option>
                                    <option value="4">Vodca</option>
                                </select>
                            </label>
                        </div>
                        <div class="groupContainerTask">
                            <span class="groupContainerTaskSpan">Druzina: </span>
                            <label>
                                <select id="druzina">
                                    <option value="12">Adam Bednar</option>
                                    <option value="13">Peter Dano</option>
                                </select>
                            </label>
                        </div>
                        <div class="groupContainerTask">
                            <input type="submit" value="potvrdit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Group Container -->
    <div class="taskCheckerContainer">
        <!-- Main group checkbox that selects all tasks in the group-->
        <div class="headOfTheGroup">
            <span class="selectAllInTheGroupSpan">Druzina: Adam Bednar</span>
        </div>
        
         <!-- Container with each member and its tasks -->
        <div class="membersOfTheGroup">
            <div class="memberOfTheGroup">
                <!-- Checkbox for selecting all tasks of the member -->
                <div class="selectAllFromTheMember" style="background-color: #b1fdfd">
                    <span class="selectAllInTheGroupSpan">Adam Bednar</span>
                </div>
                <!-- each task of the member -->
                <form>
                    <div class="groupContainerTasks" style="border: 2px dashed #b1fdfd">
                        <div class="groupContainerTask">
                            <label>
                                <span class="groupContainerTaskSpan">Pozicia: </span>
                                <select>
                                    <option value="1">Skaut</option>
                                    <option value="2" style="display: none">Rodic</option>
                                    <option value="3" selected style="font-weight: bolder">Radca</option>
                                    <option value="4">Vodca</option>
                                </select>
                            </label>
                        </div>
                        <div class="groupContainerTask">
                            <span class="groupContainerTaskSpan">Druzina: </span>
                            <label>
                                <select>
                                    <option value="12">Adam Bednar</option>
                                    <option value="13">Peter Dano</option>
                                </select>
                            </label>
                        </div>
                        <div class="groupContainerTask">
                            <input type="submit" value="potvrdit">
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>

<script>
    
</script>
';
?>