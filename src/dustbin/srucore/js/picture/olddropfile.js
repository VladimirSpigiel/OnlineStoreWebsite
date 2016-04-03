var deleteRoute = "tofix";



(function($){


    /*var o = {
        message : "Déposez vos fichiers ici",
        route : "tofix",
        deleteRoute : "tofix",
        path : "tofix",
        class : [],
        dropZone : "#tab_b",
        info : ".info",
        appenner : ".preview",
        classInput : "pictures_product",
        pictureHandler : new PictureHandler()

    }







    $.fn.dropfile = function(oo){


        o.pictureHandler.init();

        // Evite d'avoir des appels recursifs
        if(o.class.indexOf($(this).attr('class'))){
            o.class.push($(this).attr("class"));
        }else{
            bind($(this));
            return false;
        }

       if(oo) $.extend(o,oo);

        console.log(o.dropZone);


        deleteRoute = o.deleteRoute;
        o.pictureHandler.name = o.classInput;

        this.each(function(){

           bind($(this));

           this.addEventListener('drop', function(e){
               e.preventDefault();

               var files = e.dataTransfer.files;
               upload(files, $(this), 0);

           }, false)
       });

        function upload(files, area, index){
            var file = files[index];

            var xhr = new XMLHttpRequest();

            // Evenements
            xhr.addEventListener('load', function(e){
                var json = $.parseJSON(e.target.responseText);

                if(index < files.length-1)
                    upload(files,area, index+1);

                if(json.erreurs){
                    $(o.info).empty();
                    for(var i=0; i<json.erreurs.length; i++){
                        $(o.info).append("<div class='alert alert-danger'>"+json.erreurs[i]+"</div>");
                    }

                    setTimeout(function(){
                        $(o.info).empty();
                    }, 3000);
                }else{

                    $appendHtml = "<div class='img-thumbnail thumbnail' data-default='' data-id='"+json.id+"'>" +

                                    "<div>"+
                                        "<button class='btn btn-success pull-left makeDefault'><span class='glyphicon glyphicon-pushpin'></span></button>"+
                                        "<button class='btn btn-danger pull-right remove'><span class='glyphicon glyphicon-remove'></span></button>"+

                                    "</div>"+
                                        "<img class='img-responsive' src='"+ o.path+"/"+json.content+"'>" +

                                        "<input class='form-control text-center' value='"+json.content+"'>"+
                                  "</div>"
                    ;
                    $(o.appenner).append($appendHtml)

                    console.log(o.pictureHandler.name);

                    o.pictureHandler.addElementToInput({id: json.id, name:json.content, default:false});


                }


            }, false);


            xhr.upload.addEventListener('progress',function(e){
                if(e.lengthComputable){


                    var perc = (Math.round(e.loaded / e.total) * 100) + " %";
                }
            }, false)


            xhr.open('post', o.route, true);
            xhr.setRequestHeader("content-type", 'multipart/form-data');

            xhr.setRequestHeader("x-file-type", file.type);
            xhr.setRequestHeader("x-file-size", file.size);
            xhr.setRequestHeader("x-file-name", file.name);
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

            xhr.send(file);
        }

        function bind(area){
            area.bind({
                dragenter : function(e){
                    e.preventDefault();

                },
                dragover : function(e){
                    e.preventDefault();
                    $(this).addClass('hover');


                },
                dragleave : function(e){
                    e.preventDefault();
                    $(this).removeClass("hover");
                }

            });
        }
    }*/

   /* $(document.body).on("click",".makeDefault",function(e){
        e.preventDefault();

        var $thumbnail = $(this).parent().parent();
        var data =  $thumbnail.data();



        // N'est plus default
        if($thumbnail.hasClass("default")){
            $thumbnail.removeClass("default");
            $thumbnail.attr("data-default","");

            o.pictureHandler.defaultElementFromInput(data["id"], false);

            $(this).removeClass("btn-default").addClass("btn-success");

            // Devient default
        }else{

            var $elmts = $('.default').removeClass("default");
            $elmts.each(function(index,value){
                var dataElement = $(this).data();
                o.pictureHandler.defaultElementFromInput(dataElement["id"], false);
            })


            if($elmts.length>0)
                $elmts.find(".btn-default").removeClass("btn-default").addClass("btn-success");

            o.pictureHandler.defaultElementFromInput(data["id"], true);

            $thumbnail.addClass("default");
            $thumbnail.attr("data-default","default");
            $(this).removeClass("btn-success").addClass("btn-default");
        }


    })

    $(document.body).on("click",".remove",function(e){

        e.preventDefault();


        var $thumbnail = $(this).parent().parent();
        var data =  $thumbnail.data();


        var realRoute = deleteRoute.replace("PLACEHOLDER",data['id']);
        o.pictureHandler.removeElementFromInput(data["id"]);

        $.ajax({
            type: "POST",
            url: realRoute,

            success: function (data) {

                var json = $.parseJSON(data);




                if(json.succes == true){
                    $thumbnail.remove();
                }else{
                    $(o.info).html("<div class='alert alert-danger'>Une erreur est survenue ...</div>");
                }

            },
            error: function () {
                $(o.info).html("<div class='alert alert-danger'>Une erreur est survenue ...</div>");
            }
        });

        setTimeout(function(){
            //$(o.info).empty();
        }, 1000)



    })

    $(document.body).on("keyup",".thumbnail input",function(e){

        e.preventDefault();

        $thumbnail = $(this).parent();
        o.pictureHandler.renameElementFromInput($thumbnail.attr("data-id"), $(this).val())


    })*/
})(jQuery);











/*
$("form").submit(function(e){
    e.preventDefault();

    $pictures = $("form .thumbnail");
    var frm = $(this);

    var dataPictures = [];

    $pictures.each(function(){

        var tab = {};
        tab.id = $(this).data("id");
        if( $(this).hasClass("default"))
            tab.default = true;
        else
            tab.default = false;


        tab.name = $(this).find("input").val();

        dataPictures.push(tab);
    })


        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),

            success: function (data) {
                var id = $.parseJSON(data).id

                $.ajax({
                    type: frm.attr('method'),
                    url: frm.attr('action-picture')+"/"+id,
                    data: {pictures : dataPictures},

                    success: function (subdata) {


                        document.location.href = subdata;
                    },
                    error: function (a,b,c) {

                    }
                });
            },
            error: function (a,b,c) {
            }
        });





});*/