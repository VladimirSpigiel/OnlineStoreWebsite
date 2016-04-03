function operate(){

    var price_Four = $('.prixF').val();
    var price_Ttc = $('.prixT').val();
    var price_Ht = $('.prixH').val();
    var eco = $('.prixE').val();
    var marge = $('.prixM').val();
    var tva = $('.tva').find("option:selected").text();


    var prix_HT = parseFloat(price_Four) + parseFloat(eco);
    prix_HT += parseFloat((prix_HT * marge) / 100);

    var prix_TTC = parseFloat(prix_HT) + parseFloat((tva * prix_HT) / 100 );

    if(!isNaN(prix_HT))
        $('.prixH').val(prix_HT)
    else
        $('.prixH').val("Prix manquant !")

    if(!isNaN(prix_TTC))
        $('.prixT').val(prix_TTC)
    else
        $('.prixT').val("Prix ou TVA manquant !")


}


