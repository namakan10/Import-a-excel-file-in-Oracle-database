<div class="container" id ="taille">
        <div class="jumbotron" >
                <form class="form-horizontal well" action="" method="post" name="upload_csv" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Import des relevés d'heures</legend>
                        <hr class="my-4">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Fichier:</label>
                            <input type="file" name="file" id="file" class="input-large">
                        </div>

                        <div class="form-group row">
                            <label for="personne" class="col-sm-3 col-form-label">Personne:</label>
                            <select class="form-control col-sm-8" id="personne" name="prenom">
                                <?php while ($one = $membres->fetch()) {?>
                                    <option><?php echo $one['PRENOM']?></option>
                                <?php }?>
                            </select>
                        </div>

                        <div class="col-sm-3 col-form-label form-group row">
                                <button type="submit" id="submit" name="boutton" class="btn btn-primary button-loading" data-loading-text="Loading...">Charger</button>
                        </div>
                    </fieldset>
                </form>
        </div>
</div>