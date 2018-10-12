

<div class="container" id ="taille">
        <br/><br/>
        <div class="jumbotron">
                <form class="form-horizontal well" action="" method="post" name="upload_csv" enctype="multipart/form-data">
                    <fieldset>
                        <legend style="text-align: center">Import des rélevés d'heures</legend>
                        <hr class="my-4">

                        <div class="form-group row">
                            <label for="personne" class="col-sm-4 col-form-label">Nom et prénom:</label>
                            <select class="col-sm-7" id="personne" name="prenom">
                                <?php while ($one = $membres->fetch()) {?>
                                    <option><?php echo $one['NOM']." ".$one['PRENOM']?></option>
                                <?php }?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="month" class="col-sm-4 col-form-label">Mois:</label>
                            <select class="col-sm-7" id="month" name="month">
                                <option>Janvier</option>
                                <option>Fevrier</option>
                                <option>Mars</option>
                                <option>Avril</option>
                                <option>Mai</option>
                                <option>Juin</option>
                                <option>Juillet</option>
                                <option>Aôut</option>
                                <option>Septembre</option>
                                <option>Octobre</option>
                                <option>Novembre</option>
                                <option>Decembre</option>
                            </select>
                        </div>
                        <div class="form-group row" style="margin-left: 3px; margin-right: 28px">
                            <input type="file" name="file" id="file" class="filestyle" data-text="Sélectionner le fichier" data-buttonBefore="true" data-btnClass="btn-light" required>
                        </div>

                        <div class="col-sm-3 col-form-label form-group row">
                            <button type="submit" id="submit" name="boutton" class="btn btn-primary button-loading" data-loading-text="Loading...">Charger</button>
                        </div>
                    </fieldset>
                </form>
        </div>
</div>