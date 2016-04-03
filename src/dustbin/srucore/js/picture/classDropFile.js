var DropFile = function(dropZone,multiple, route, path, deleteRoute){

        this.route = "tofix";
        this.deleteRoute = deleteRoute;
        this.route = route;
        this.class = [];
        this.dropZone = dropZone;
        this.multiple = multiple;
        this.info = ".info";
        this.path = path;
        this.appenner = ".preview";
        this.classInput = "pictures_product";
        this.pictureHandler = new PictureHandler(".pictures",dropZone, deleteRoute);

        var instance = this;


        this.bind = function(area){
            $(area).bind({
                dragenter : function(e){
                    e.preventDefault();
                    e.stopPropagation();



                },
                dragover : function(e){
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).addClass('hover');


                },
                dragleave : function(e){
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).removeClass("hover");
                }
            })

            $(dropZone).on('drop', function(e, ui){
                e.preventDefault();
                    var files = e.originalEvent.dataTransfer.files;
                if(instance.multiple == false && (files.length > 1 || instance.pictureHandler.array.length > 0)){
                    $(dropZone +" .picturesInfo").html("<div class='alert alert-danger'> Vous ne pouvez upload qu'un seul fichier</div>")

                    setTimeout(function(){
                        $(dropZone +" .picturesInfo").empty();
                    }, 2000)
                }else
                {
                    var uploader = new Upload(files, instance.dropZone , instance.route, instance.path, instance.deleteRoute, instance.pictureHandler);
                    uploader.uploadIt();
                }



            })
        }


    //Execution de la fonction
    this.bind(this.dropZone);




}
