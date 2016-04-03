var PictureHandler = function(name,dropZone, deleteRoute){
    this.name = name;
    this.dropZone = dropZone;
    this.deleteRoute = deleteRoute;
    this.array = [];

    var instance = this;




    this.refreshInput = function (){

        $(instance.dropZone +' input' + instance.name).val(JSON.stringify(instance.array));
    }

    this.init = function (){
        var tabDefault = 0;

        $(instance.dropZone + ' .thumbnail').each(function(index,value){
            var id = $(this).attr("data-id");
            var defaultt= $(this).attr("data-default");

            if(defaultt == "default"){
                defaultt = true;
                tabDefault++;
            }else{
                defaultt = false;
            }
            var name = $(this).find("input").val();

            instance.addElementToInput({id: id, name: name, default: defaultt})

        })

        if(tabDefault == 0){

            console.log($(instance.dropZone + ' .thumbnail .makeDefault').first().trigger("click"));

        }

    }

    this.addElementToInput = function(data){
        instance.array.push(data);


        instance.refreshInput();
    }

    this.renameElementFromInput = function(id, newName){
        var index = instance.getElementFromInput(id);


        instance.array[index]["name"] = newName;
        instance.refreshInput();
    }

    this.getElementFromInput = function(id){
        for(var i=0; i< instance.array.length; i++){

            if(instance.array[i]["id"] == id){
                return i;

            }
        }
    }

    this.removeElementFromInput = function(id){
        var index = instance.getElementFromInput(id);
        instance.array.splice(index,1);
        instance.refreshInput();

    }

    this.defaultElementFromInput = function(id, etat){
        var index = instance.getElementFromInput(id);

        instance.array[index]["default"] = etat;
        instance.refreshInput();
    }



    $(document.body).on("click",instance.dropZone +" .makeDefault",function(e){


        e.preventDefault();

        var $thumbnail = $(this).parent().parent();


        var data =  $thumbnail.data();

        // N'est plus default
        if($thumbnail.hasClass("default")){

            if($(instance.dropZone + " .thumbnail").length > 1){

                $thumbnail.removeClass("default");
                $thumbnail.attr("data-default","");

                instance.defaultElementFromInput(data["id"], false);

                $(this).removeClass("btn-default").addClass("btn-success");
            }
            // Devient default
        }else{

            var $elmts = $(instance.dropZone +' .default').removeClass("default");
            $elmts.each(function(index,value){
                var dataElement = $(this).data();
                instance.defaultElementFromInput(dataElement["id"], false);
            })


            if($elmts.length>0)
                $elmts.find(".btn-default").removeClass("btn-default").addClass("btn-success");

            instance.defaultElementFromInput(data["id"], true);

            $thumbnail.addClass("default");
            $thumbnail.attr("data-default","default");
            $(this).removeClass("btn-success").addClass("btn-default");
        }

    })



    $(document.body).on("click",instance.dropZone +" .remove",function(e){

        e.preventDefault();


        var $thumbnail = $(this).parent().parent();
        var data =  $thumbnail.data();

        var realRoute = instance.deleteRoute.replace("PLACEHOLDER",data['id']);

        instance.removeElementFromInput(data["id"]);

        if($(instance.dropZone + " .thumbnail.default").length == 0){
            $(instance.dropZone + " .thumbnail .makeDefault").first().trigger("click")
        }


        $.ajax({
            type: "POST",
            url: realRoute,

            success: function (data) {

                var json = $.parseJSON(data);


                if(json.succes == true){
                    $thumbnail.remove();
                }else{
                    //$(o.info).html("<div class='alert alert-danger'>Une erreur est survenue ...</div>");
                }

            },
            error: function () {
                //$(o.info).html("<div class='alert alert-danger'>Une erreur est survenue ...</div>");
            }
        });

        setTimeout(function(){
            //$(o.info).empty();
        }, 1000)



    })

    $(document.body).on("keyup",instance.dropZone +" .thumbnail input",function(e){

        e.preventDefault();

        $thumbnail = $(this).parent();
        instance.renameElementFromInput($thumbnail.attr("data-id"), $(this).val())


    })

    this.init();

}