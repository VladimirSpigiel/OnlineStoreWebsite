function addAjax(identifier, title, path, multiple) {

    // Sert à connaitre le champ select
    var multiselectClass = "." + identifier;
    // Sert à connaitre le formulaire
    var formClass = "." + identifier + "Form";
    // Sert à connaitre le modal bootstrap
    var modal = identifier + "Modal";
    // Son ID
    var modalID = "." + identifier + "Modal";
    // Connaitre le boutton qui fera apparaitre le modal
    var button = ".new_" + identifier;

    if($(button).length == 0)
        console.log("Pensez à ajouter la class 'new_"+identifier+"' au bouton de création dynamique")



    // append du modal en fonction des variables
    $(".modals").append('<div class="modal fade ' + modal + '" id="' + modal + '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
        '<div class="modal-dialog">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
        '<h4 class="modal-title" id="myModalLabel">' + title + '</h4><br>' +
        '<div class="info"></div>' +

        '</div>' +
        '<div class="modal-body">' +

        '</div>' +
        '<div class="modal-footer">' +

        '</div>' +
        '</div>' +
        '</div>' +
        '</div>')


    // Lien du modal et du bouton
    $(button).attr("data-toggle", "modal").attr("data-target", "#" + modal);


    // Action du bouton "Ajouter une categorie" par exemple ....
    $(button).click(function () {

        $(modalID + ' .info').empty();

        // Récupération du formulaire distant
        $.ajax({
            url: path,
            type: "GET",
            beforeSend: function () {

                $(modalID + ' .modal-body').html("Chargement ...");

            },
            success: function (data) {

                $(modalID + ' .modal-body').html(data);

            },

            error: function () {
                $(modalID + ' .modal-body').html("Impossible de récupéré le formulaire distant .... <br>" +
                    "L'opération devra se faire manuellement");
            }

        })
    })


    // Lorsque soumission du formulaire
    $(document.body).on("submit", formClass, function(e) {


        e.preventDefault();

        var frm = $(formClass);

        // Envoie de tout le formulaire
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            beforeSend: function () {

                $(modalID + ' .info').html("<div class='alert alert-info'> En cours de traitement ...</div>")
            },
            success: function (data) {
                $(modalID + ' .info').html("<div class='alert alert-success'>Création effectuée ! </div>")

                if(multiple == true){
                    var newOption = [data.id, data.name];
                    // Ajout de l'option dans la liste déroulante
                    $(multiselectClass).multiselect("addOption", newOption);
                }else{
                    $(multiselectClass).append("<option value='"+data.id+"'>"+data.name+"</option>")
                }

                setTimeout(function () {
                    $(modalID + ' .close').trigger('click');
                }, 1000)
            },
            error: function () {
                $(modalID + ' .info').html("<div class='alert alert-danger'>Une erreur est survenue ...</div>")
            }
        });


    })
}

