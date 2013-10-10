<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf-8" />
    <title>Convert</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="blueimp/css/jquery.fileupload.css">
</head>
<body>

<div class="well" style="margin:10px;">
    <h4>Konvertering och komprimering</h4>
    <p>Här hittar du två olika funktioner för dina filer:<br/>
        1. Konvertering (icke-PDF), om din fil exempelvis är i bildformat så försöker vi konvertera den till PDF här.<br/>
        2. Komprimering (PDF-fil), om din fil redan är i PDF-format så försöker vi pressa ihop den till en mindre storlek.<br/>
        Vilken av funktionerna som tillämpas beror på den fil som du för in i denna ruta.
    </p>
    <p><strong>OBS!</strong>&nbsp;Vi tar inte ansvar för innehållet efter konvertering eller komprimering. Du måste själv kontrollera den fil du får tillbaka!</p>
    <p><strong>"Drag och släpp" din fil hit eller klicka på "Välj filer" för att konvertera dokument.</strong></p>
    <p>
        <span class="btn btn-success fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Välj filer...</span>
            <!-- The file input field used as target for the file upload widget -->
            <input id="fileupload" type="file" name="files[]" multiple>
        </span>
    </p>
    <p>
        Går det inte att konvertera filerna kan du skicka ett e-postmeddelande till <a href="mailto:andreas@flowcom.se">andreas@flowcom.se</a> för hjälp!
    </p>
    <div id="progress" class="progress-bar progress progress-striped active">
        <div class="bar"></div>
    </div>
    <div id="files" class="files"></div>
</div>

<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="blueimp/js/vendor/jquery.ui.widget.js"></script>
<script src="blueimp/js/jquery.iframe-transport.js"></script>
<script src="blueimp/js/jquery.fileupload.js"></script>

<script>
    /*jslint unparam: true */
    /*global window, $ */
    $(function () {
        'use strict';
        var url = 'server/';
        $('#fileupload').fileupload({
            url: url,
            apc: true,
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {

                    file['unique'] = cuniq();

                    file['thumbnailUrl'] = "/img/print_64x64.png";

                    if( file.error ){
                        $('#files').append('<div class="alert alert-error">' +
                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                            '<strong>' + file.name + '</strong><br/>' + file.error + '.\n</div>');
                    }
                    else{

                        if( file.type == 'application/pdf' ){

                            $('#files').append('' +
                                '<div class="media" id="container_' + file.unique + '">' +
                                '<a target="_blank" class="pull-left" href="' + file.url + '">' +
                                '<img class="media-object" src="' + file.thumbnailUrl + '">' +
                                '</a>' +
                                '<div class="media-body" id="message_' + file.unique + '">' +
                                '<h4 class="media-heading">' + file.name + '</h4>' +
                                '<div id="action_' + file.unique + '"><span class="action">Komprimerar filen till en mindre PDF. Det här kan ta upp till ett par minuter...</span></div>' +
                                '<img id="ajax_' + file.unique + '" src="img/ajax-loader.gif" />' +
                                '</div>' +
                                '</div>');

                            $.ajax({
                                url: '/compress.php',
                                method: "post",
                                data: { url: file.url },
                                async: false,
                                success: function( result ) {
                                    $('#ajax_' + file.unique ).hide();
                                    if (result == '1') {
                                        var filename = decodeURI( file.url );
                                        filename = filename.replace(/.*\//, '');
                                        filename = filename.replace(/\.[^/.]+$/, "");
                                        var url = file.url.replace(/\.[^/.]+$/, "") + '-comp.pdf';
                                        $('#action_' + file.unique).html('<div class="alert alert-success">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong>Klart!</strong><br/><a download="' + filename + '-comp.pdf" href="' + url + '" target="_blank">Du kan hämta den komprimerade filen genom att <span style="text-decoration: underline;"><strong>klicka här</strong></span>!</a>\n</div>');
                                    } else {
                                        $('#action_' + file.unique).html('<div class="alert alert-error">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong>Felmeddelande</strong><br/>Det gick inte att komprimera filen.\n</div>');
                                    }
                                }
                            });

                        }
                        else{

                            $('#files').append('' +
                                '<div class="media" id="container_' + file.unique + '">' +
                                '<a target="_blank" class="pull-left" href="' + file.url + '">' +
                                '<img class="media-object" src="' + file.thumbnailUrl + '">' +
                                '</a>' +
                                '<div class="media-body" id="message_' + file.unique + '">' +
                                '<h4 class="media-heading">' + file.name + '</h4>' +
                                '<div id="action_' + file.unique + '"><span class="action">Konverterar filen till PDF. Det kan ta ett par minuter...</span></div>' +
                                '<img id="ajax_' + file.unique + '" src="img/ajax-loader.gif" />' +
                                '</div>' +
                                '</div>');

                            $.ajax({
                                url: '/convert.php',
                                method: "post",
                                data: { url: file.url },
                                async: false,
                                success: function( result ) {
                                    $('#ajax_' + file.unique ).hide();
                                    if (result == '1') {
                                        var filename = decodeURI( file.url );
                                        filename = filename.replace(/.*\//, '');
                                        filename = filename.replace(/\.[^/.]+$/, "");
                                        var url = file.url.replace(/\.[^/.]+$/, "") + '.pdf';
                                        $('#action_' + file.unique).html('<div class="alert alert-success">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong>Klart!</strong><br/><a download="' + filename + '.pdf" href="' + url + '" target="_blank">Du kan hämta den konverterade filen genom att <span style="text-decoration: underline;"><strong>klicka här</strong></span>!</a>\n</div>');
                                    } else {
                                        $('#action_' + file.unique).html('<div class="alert alert-error">' +
                                            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                                            '<strong>Felmeddelande</strong><br/>Det gick inte att konvertera filen.\n</div>');
                                    }
                                }
                            });

                        }

                    }
                });
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .bar').css(
                    'width',
                    progress + '%'
                );
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });

    var a = 1;
    function cuniq() {
        var d = new Date(),
            m = d.getMilliseconds() + "",
            u = ++d + m + (++a === 10000 ? (a = 1) : a);

        return u;
    }





</script>

</body>
</html>
