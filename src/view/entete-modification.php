
<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#modif" role="button" aria-expanded="false" aria-controls="modif">
        Modifier une ligne
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#supprimer" aria-expanded="false" aria-controls="supprimer">
        Supprimer une ligne
    </button>
    <a class="btn btn-primary" href="../../../Import_database/annuler.php">
        Annuler
    </a>
</p>


<div class="collapse multi-collapse" id="modif">
    <h2 style="text-align: center">Modification</h2>
    <div class="card card-body">
        <form method="post" action="">
            <fieldset>
                <div class="form-group">
                    <div class="form-group row">
                        <label for="id" class="col-sm-2 col-form-label">ID du champ à modifier</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="id" type="text" name="id">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="select" class="col-sm-2 col-form-label">Champ à modifier</label>
                        <div class="col-sm-10">
                            <select class="form-control" id= "select" name="select">
                                <option>Date</option>
                                <option>Numéro de projet</option>
                                <option>Phase</option>
                                <option>Description</option>
                                <option>Heures regulières</option>
                                <option>Heures supplementaires</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="modif" class="col-sm-2 col-form-label">Modification</label>
                    <div class="col-sm-10">
                        <input class="form-control" id="modif" type="text" name="modif">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" name="modifier">Modifier</button>
            </fieldset>
        </form>
    </div>
</div>


<div class="collapse multi-collapse" id="supprimer">
    <h2 style="text-align: center">Suppression</h2>
    <div class="card card-body">
        <form method="post">
            <fieldset>
                <div class="form-group">
                    <div class="form-group row">
                        <label for="date" class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="date" type="text" name="date_supp">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="proj" class="col-sm-2 col-form-label">Numero de projet</label>
                        <div class="col-sm-10">
                            <input class="form-control" id="proj" type="text" name="proj_supp">
                        </div>
                    </div>
                <button type="submit" class="btn btn-primary" name="supprimer">Supprimer</button>
            </fieldset>
        </form>
    </div>
</div>

