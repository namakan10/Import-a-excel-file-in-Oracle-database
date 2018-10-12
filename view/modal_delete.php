<div class="modal hide fade" id="confirmModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmation de la suppression</h3>
            </div>
            <div class="modal-body">
                <p>Etes-vous sûr de vouloir supprimer cet élément ?</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-danger" id="confirmModalNo">Non</a>
                <a href="#" class="btn btn-success" id="confirmModalYes">Oui</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var theHREF;

        $(".confirmModalLink").click(function(e) {
            e.preventDefault();
            theHREF = $(this).attr("href");
            $("#confirmModal").modal("show");
        });

        $("#confirmModalNo").click(function(e) {
            $("#confirmModal").modal("hide");
        });

        $("#confirmModalYes").click(function(e) {
            window.location.href = theHREF;
        });
    });
</script>