var Upload = function(files, area, route, path, deleteRoute, pictureHandler){

    this.index = 0;
    this.files = files;
    this.area = area;
    this.route = route;
    this.path = path;
    this.deleteRoute = deleteRoute;
    this.pictureHandler = pictureHandler;



    var instance = this;

    this.uploadIt = function(index){
        if(index == undefined){
            index = instance.index;
        }
        var file = this.files[index];
        var xhr = new XMLHttpRequest();

        xhr.addEventListener('load', function(e){

            var json = $.parseJSON(e.target.responseText);

            if(index < instance.files.length-1)
                instance.uploadIt(index+1);

            if(json.erreurs){

                for(var i=0;i<json.erreurs.length;i++){
                    $(area +" .picturesInfo").append("<div class='alert alert-danger'>"+json.erreurs[i]+"</div>")

                }

                setTimeout(function(){
                    $(area +" .picturesInfo").empty();
                }, 3000)

            }else{
              

                if($(instance.area + ' .thumbnail').length == 0){

                    $appendHtml = "<div class='img-thumbnail thumbnail default' data-default='default' data-id='"+json.id+"'>" +

                        "<div>"+
                        "<button class='btn btn-default pull-left makeDefault'><span class='glyphicon glyphicon-pushpin'></span></button>"+
                        "<button class='btn btn-danger pull-right remove'><span class='glyphicon glyphicon-remove'></span></button>"+

                        "</div>"+
                        "<img class='img-responsive' src='"+ instance.path+"/"+json.content+"'>" +

                        "<input class='form-control text-center' value='"+json.content+"'>"+
                        "</div>"
                    ;
                    $(area + " .preview").append($appendHtml)
                    instance.pictureHandler.addElementToInput({id: json.id, name:json.content, default:true});
                }else{
                    $appendHtml = "<div class='img-thumbnail thumbnail' data-default='' data-id='"+json.id+"'>" +

                        "<div>"+
                        "<button class='btn btn-success pull-left makeDefault'><span class='glyphicon glyphicon-pushpin'></span></button>"+
                        "<button class='btn btn-danger pull-right remove'><span class='glyphicon glyphicon-remove'></span></button>"+

                        "</div>"+
                        "<img class='img-responsive' src='"+ instance.path+"/"+json.content+"'>" +

                        "<input class='form-control text-center' value='"+json.content+"'>"+
                        "</div>"
                    ;
                    $(area + " .preview").append($appendHtml)
                    instance.pictureHandler.addElementToInput({id: json.id, name:json.content, default:false});

                }











            }

        }, false);

        xhr.open('post', instance.route, true);
        xhr.setRequestHeader("content-type", 'multipart/form-data');

        xhr.setRequestHeader("x-file-type", file.type);
        xhr.setRequestHeader("x-file-size", file.size);
        xhr.setRequestHeader("x-file-name", file.name);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        xhr.send(file);

    }





}