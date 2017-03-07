
<!-- Radio Parlamento 
http://expressao.xyz/radio/
https://github.com/fsilva/radio-parlamento

RC1  23/02/2017
-->


<!DOCTYPE html>
<html>
    
<head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <title>Ελληνική Ραδιοφωνία</title>
    
        <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    
<style>
body { background-color: #ffffff; 
        background-image: url("hellenic.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center; 
        background-size:cover; }    
#backdiv {background-color:  rgba(255,255,255,0.92); max-width: 600px; margin: 20px; border-radius: 10px;}
/*#forediv {opacity: 1; }*/
h1 { font-family: "Helvetica"; font-size: 48px; margin:0;}
h2 { font-family: "Times"; font-size: 24px;}
h3 { font-family: "Helvetica"; font-style: normal; font-weight: normal; }
h4 { font-family: "Helvetica"; font-style: normal; font-weight: normal; font-size: 14px;}
h5 { font-family: "Helvetica"; font-style: normal; font-weight: normal; }
h6 { font-family: "Helvetica";   font-style: normal; font-weight: normal;  font-size: 10px;}
a { font-family: "Helvetica"; font-style: normal; font-weight: normal; font-size: 16px; text-decoration: underline; color: black;}
p { font-family: "Times"; font-style: normal; font-weight: normal; margin: 0; }}
table, th, td {  margin: 0; border: 1px solid black; text-align:center;}
table{  width: 95%;  }
select {text-align-last:center;}
#footer{ font-family: "verdana";   font-style: normal; font-weight: normal;  font-size: 10px;}
#afooter { font-family: "verdana"; font-size: 8px;}
#tradutor {background-color:  rgba(255,255,255,0.75); width: 90%; margin: 20px; border-radius: 10px;}
#iosbutton {   position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);}
#novoice {  background-color: white;  border-radius: 10px;  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);}
#BRvoice {  background-color: white;  border-radius: 10px;  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);}
</style>
    
</head>
<body>

    
    <!-- create hidden DOM element with all the data from the random assembleia page -->
    <div id="dom-target_PT" style="display: none;">
        <?php
        
        ini_set('display_errors', 1);
        error_reporting(E_ALL ^ E_NOTICE);

        $valid_session = 0;

        // determine if GET parameters specify valid date
        $random_date = 1;
        if(!(empty($_GET["d"]) || empty($_GET["m"]) || empty($_GET["y"])))
        {
            $random_date = 0;
            $day = $_GET["d"]-1; //need -1 for the following logic. note the 0 and 30 in the if
            $month = $_GET["m"];
            $year = $_GET["y"];
            if($day < 0 || $day > 30 || $month < 1 || $month > 12 || $year < 1976 || $year > 2015)
                $random_date = 1;
        }
        
        // clamp date to limits
        if($year > 2017 && $month > 2 && $day > 03)
        {
           $year = 2017; $month = 2; $day = 03-1; 
        }
        
        // clamp date to limits
        if($year > 2017 && $month > 3)
        {
           $year = 2017; $month = 3; $day = 3-1; 
        }
        
        
        if($year < 2010)
        {
           $year = 2010; $month = 01; $day = 11-1; 
        }
        
        // loop until we find an existing session
        // start at current specified or random date, and check if demo.cracia.org file exists. if not try next day. do that 100 times, then just quit
        $count = 0;
        do
        {
            if($random_date)
            {
                $day = intval(rand(1,31));
                $month = intval(rand(1,12));
                $year = intval(rand(2013, 2017));
            }else
            {
                // try next day until we find a valid session
                $day = $day + 1;
                if($day > 31)
                {
                    $day = 1;
                    $month = $month + 1;
                    if($month > 12)
                    {
                        $month = 1;
                        $year = $year + 1;
                    }
                }
            }

            #$URL0 = sprintf('http://demo.cratica.org/sessoes/%d/%02d/%02d/', $year, $month, $day );
            
               $URLlocal = sprintf('EL/EL_%04d-%02d-%02d.txt', $year, $month, $day );
                #$URLlocal = sprintf('backup170221/sessoes/%d/%02d/%02d/index.html', $year, $month, $day );
                
                #echo $URLlocal;
                if(file_exists($URLlocal) == 1)
                    $valid_session = 1;
                
                $URLfinal = $URLlocal;
            
            
            if($count > 100)
                return;
            $count = $count + 1; // protection to prevent server working indefinitely
            
        }while($valid_session == 0);
        
        //echo sprintf('<p>%s</p>',$URLlocal); //first <p> is demo.cracia.org link
        
        
        $result = file_get_contents($URLfinal);
        $result = mb_convert_encoding($result, "UTF-8");
        
        $str = strtok($result, "\n");
        echo(sprintf('<p>%s</p>',$str));
        
        echo(sprintf('<p>%04d-%02d-%02d</p>',$year, $month, $day ));

        //preprocess data a bit, restructure <p>'s
        // remove newlines
        $result = str_replace("\r", '', $result);
        $result = str_replace("\n\n\n\n", '\n', $result);
        $result = str_replace("\n\n\n", '\n', $result);
        $result = str_replace("\n\n", '\n', $result);
        $result = str_replace("\n\n", '\n', $result);
        $result = str_replace(array("\n", "\r"), '</p><p>', $result);
        
        
        $result = str_replace(".", '.</p><p>', $result);
        
        // 9) remove intermediate paragraph tags
        $result = str_ireplace('</p><p>',' ',$result);
        // 10) if there is a '. ', add paragraph tags
        $result = str_ireplace('. ','.</p><p>',$result);
        // 11) if there is a '! ', add paragraph tags
        $result = str_ireplace('! ','!</p><p>',$result);
        // 12) if there is a '? ', add paragraph tags
        $result = str_ireplace('? ','?</p><p>',$result);
        
        
        // Process DOM, find appropriate element
        $output = 0;
                
                echo $result; // output only the element whose class is "entry-content"
                
                // Count words for wordcloud
                $text = (strip_tags(nl2br($result)));
                // Remove punctuation and some obvious words
                $text = str_ireplace(':', '',$text);
                $text = str_ireplace(',', '',$text);
                $text = str_ireplace('.', '',$text);
                $text = str_ireplace('!', '',$text);
                $text = str_ireplace('?', '',$text);
                $text = str_ireplace('nicht', '',$text);
                $text = str_ireplace('beifall', '',$text);
                $text = str_ireplace('haben', '',$text);
                $text = str_ireplace('dieser', '',$text);
                $text = str_ireplace('diese', '',$text);
                $text = str_ireplace('diesem', '',$text);
                $text = str_ireplace('einen', '',$text);
                $text = str_ireplace('einem', '',$text);
                $text = str_ireplace('schon', '',$text);
                $text = str_ireplace('einer', '',$text);
                $text = str_ireplace('anderen', '',$text);
                $text = str_ireplace('deutschland', '',$text);
                $text = str_ireplace('keine', '',$text);
                $text = str_ireplace('werden', '',$text);
                $text = str_ireplace('herren', '',$text);
                $text = str_ireplace('damen', '',$text);
                $text = str_ireplace('allen', '',$text);
                $text = str_ireplace('sondern', '',$text);
                $text = str_ireplace('jetzt', '',$text);
                $text = str_ireplace('wollen', '',$text);
                $text = str_ireplace('damit', '',$text);
                $text = str_ireplace('fragen', '',$text);
                $text = str_ireplace('frage', '',$text);
                $text = str_ireplace('antwort', '',$text);
                $text = str_ireplace('sident', '',$text);
                $text = str_ireplace('sowie', '',$text);
                $text = str_ireplace('sowie', '',$text);
                $text = str_ireplace('immer', '',$text);
                $text = str_ireplace('ihnen', '',$text);
                $text = str_ireplace('sagen', '',$text);
                $text = str_ireplace('einmal', '',$text);
                
                $words = array_count_values(str_word_count($text, 1, 'çáéíóúàèìòùãõñâêîôû'));
                arsort($words);
                    
                // echo hidden word count table #wordcloud
                // only consider words with 5 or more letters, except for party names
                echo '<table id="wordcloud">';
                foreach($words as $word => $count):
                    if($count > 25 && strlen($word)>4)
                    {
                            echo '<tr><td>';
                            echo ucfirst($word);
                            echo '</td><td>';
                            echo $count;
                            echo '</td></tr>';   
                    }
                endforeach;
                echo '</table>';       
                
            

        
        

        ?>
    </div>  
    
    <!-- Visible markup start here -->
    
    <center id="maindiv" style='display: none;'>
    <div id="backdiv" >
        
        
        
    <br>
        <div style='font-size:40px; font-family: helvetica, sans-serif;' id="titulo">
            <table>
                <td>
                <tr> <img height=40px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Greece.svg"> </tr>
                <tr> Ελληνική Ραδιοφωνία </tr>
                <tr> <img height=40px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Greece.svg"> </tr>
                </td>
            </table>
        </div>
        
    <br>
    <h2 style="margin: 0; font-size:30px" id="data"></h2>
        <a href="./index.el.php" style="margin: 0; font-size: 14px; text-decoration: underline;">τυχαία ημερομηνία</a>
        <a id="escolherData" style="margin: 0; margin-left: 1em; font-size: 14px; text-decoration: underline;">επιλέξτε μια ημερομηνία</a>
    
        <br>
        <div id="escolherData_div" style="display:none;">
        </div>
        
        <table style="margin-top: 50px;">
            <tr>
                <td><center><img src="microphone-1295666_960_720.png" width="48px" onclick="setPT()" id="micPT"></center></td>
                <td><center><div id="deixaPT" onclick="setPT()" style="margin: 5px; overflow-y: scroll; height: 18em; border:  1px solid lightgray; border-radius: 10px; max-width: 95%;"></div></center></td>
            </tr>

        </table>
            
        <br>
        
        <div id="tradutor" style="margin-top: 50px; ">
        
        <h4 style="margin: 0; font-weight: bold; font-size: 24px;">μεταφραστής</h4>
            <select id="lang" onclick="setTimeout(setEN,2500)" style="margin: 0;">
                  <option value="ar;Arabic Female">عربى/αραβικός</option>
                  <option value="zh-TW;Chinese Female">中文/κινέζικα</option>
                  <option value="ko;Korean Female">한국어/Κορέας</option>
                  <option value="cs;Czech Female">čeština/Τσέχος</option>
                  <option value="de;Deutsch Female">Deutsch</option>
                  <option value="da;Danish Female">Dansk/Δανός</option>
                  <option selected="selected" value="en;UK English Female">English</option>
                  <option value="es;Spanish Female">Español</option>
                  <option value="el;Greek Female">Ελληνικά/Griechisch</option>
                  <option value="fr;French Female">Français</option>
                  <option value="hi;Hindi Female">हिंदी/Χίντι</option>
                  <option value="hu;Hungarian Female">Magyar/Ούγγρος</option>
                  <option value="it;Italian Female">Italiano</option>
                  <option value="ja;Japanese Female">日本語/Ιαπωνικά</option>
                  <!-- <option value="la;Latin Female">Latim</option> <!-- Latin crashes some browsers -->
                  <option value="nl;Dutch Female">Nederlands</option>
                  <option value="pl;Polish Female">Polskie/Πολωνός</option>
                  <option value="pt;Portuguese Female">Português</option>
                  <option value="pt-BR;Brazilian Portuguese Female">PT Brasileiro</option>
                  <option value="ro;Romanian Male">Română/ρουμανικός</option>
                  <option value="ru;Russian Female">русский/ρωσικός</option>
                  <option value="sv;Swedish Female">Svenska/σουηδικά</option>
                  <option value="fi;Finnish Female">Suomi/φινλανδικός</option>
                  <option value="no;Norwegian Female">Norsk/Νορβηγός</option>
                  <option value="tr;Turkish Female">Türk/τουρκική</option>
                  <option value="th;Thai Female">ไทย/Ταϊλάνδης</option>
                </select>
            
        <table>
            <tr>
                <td><center>
                    
                    <img src="microphone-1295666_960_720.png" width="48px" onclick="setEN()" id="micEstrangeiro"></center>
                    
                </td>
                <td><h4 id="deixaEN" onclick="setEN()"></h4></td>
            </tr>    
        </table>
            
        </div>
    
    
    
        <br>
        <center>
        <img src="Mute_Icon.svg" width="48px" onclick="muteToggle()" id="mute"></center>
        
        <a id="linkDemocracia" href="http://demo.cratica.org" style="margin: 20px;">Ακολουθεί το πλήρες κείμενο</a> 
        <br> <br>
        <!-- <button onclick="music1()">Musica de fundo 1</button>
        
        <button onclick="music2()">Musica de fundo 2</button>-->
        
        <!-- <button onclick="music3()">Musica de fundo 3</button>-->
        <br>
        <iframe src="" frameborder="0" scrolling="no" id="myFrame" style="display: none;"></iframe>
        
       <!-- <h5>Σε αυτή την ενότητα:</h5>
        <div id="wordcloud2" style="display: block;  
        width: 500px;
        height: 200px; margin: 0;"></div>-->
        
        <div style="max-width:400px; margin-top: 50px;">
            
           
            
            <div id="footer">Ελληνική μεταγραφές κοινοβούλιο προέρχονται από  <a id="afooter" href="http://www.hellenicparliament.gr/">hellenicparliament.gr</a></div>
            <div id="footer">Πολύγλωσσο Αυτόματη μετάφραση με<a id="afooter" href="https://translate.google.com/">google translate</a></div>
            <div id="footer">Πολύγλωσσο αυτόματη ανάγνωση με <a id="afooter" href="https://responsivevoice.org/">responsivevoice.js</a></div>
            
            <div id="footer">CC2 Χορηγία εικόνας φόντου της  <a id="afooter" href="https://commons.wikimedia.org/wiki/File:Hellenic_Parliament-MPs_swearing_in.png">ΠΑΣΟΚ und wikipedia</a></div>
            <div id="footer">Λειτουργεί καλύτερα σε Mac και iOS, έχουν πολύ καλύτερη TTS φωνές.</div>
            <!-- <div id="footer">Produção <a id="afooter" href="expressao.xyz">expressao.xyz</a></div> -->
            <div id="footer">πάρετε τον πηγαίο κώδικα σε <a id="afooter" href="https://github.com/fsilva/radio-parlamento">GitHub</a></div> 
            <br>
            <button style="font-size: 12px" onclick=" $('#BRvoice').show();$('#maindiv').hide();">Διαβάστε Αλλαγή ελληνική γλώσσα κειμένου.</button>
            
        </div>
        <br/>
         <div style='align:center;'><a href="./index.php"> <img height=20px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" href="https://commons.wikimedia.org/wiki/File:Flag_of_Portugal.svg">Radio Parlamento</a><img height=20px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Portugal.svg" href="https://commons.wikimedia.org/wiki/File:Flag_of_Portugal.svg"></div>
        <br/>
         <div style='align:center;'><a href="./index.de.php"> <img height=20px src="https://upload.wikimedia.org/wikipedia/en/b/ba/Flag_of_Germany.svg">Radio Bundestag</a><img height=20px src="https://upload.wikimedia.org/wikipedia/en/b/ba/Flag_of_Germany.svg"></div>
        
        <br/>
         <div style='align:center;'><a href="./index.dk.php"> <img height=20px src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Flag_of_Denmark.svg">Radio Folketing</a><img height=20px src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Flag_of_Denmark.svg"></div>
        
        <br/>
         <div style='align:center;'><a href="./index.el.php"> <img height=20px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Greece.svg">Ελληνική Ραδιοφωνία</a><img height=20px src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Flag_of_Greece.svg"></div>
        
    </div></center>
    
    <form method="get" id="dateForm" action="index.de.php">
        <input type="hidden" id="y" name="y" value="">
        <input type="hidden" id="m" name="m" value="">
        <input type="hidden" id="d" name="d" value="">
    </form>
        
    <div id='bigplaybutton' style='display: none;'><img src="play.png" id='iosbutton' width="50%" onclick="iOSbuttonClick()"><p>Play</p></div>
    
    
    <div id='BRvoice' style='display: none;text-align:center;'><div style="margin:10px; center:"><h2></h2> 
        
        
        <h2>Επιλέξτε να διαβάσει το κείμενο στη γερμανική γλώσσα μια φωνή:</h2>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("Dutch Female")'> Dutch </button> <p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("Swedish Female")'> Swedish </button><p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("Italian Female")'> Italian </button><p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("French Female")'> Franch </button><p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("UK English Female")'> English </button><p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("Korean Female")'> Coreano </button><p></p>
        <button style="font-size:20px; width:50%; margin: 5px;" onclick='init("Hindi Female")'> Hindu </button>
        <p></p>
        
        
        
        
        
        </div></div>
    
    
    <!-- Visible markup ends here -->
    
    
    <script src="jquery.min.js"></script>
    <script src='responsivevoice.js'></script>
    <script src="jqcloud/dist/jqcloud.min.js"></script>
    <link rel="stylesheet" href="jqcloud/dist/jqcloud.min.css">
    <link rel="stylesheet" href="jquery-ui.min.css">
    <script src="jquery-ui.min.js"></script>
    <script type="text/javascript" src="datepicker-pt.js"></script>
    <script src="jquery.scrollTo.min.js"></script>
    
    <script type="text/javascript">
        
        //execution starts here
        
        var language = 'el';
        var mute = 0;
        var words = [];
        var items = [];
        var tts_language = 'Greek Female';
        
        
        
        // test if we are running in iOS - if yes then temporarily hide main view and 
        //    show big play button, whose callback will show main view and run init()
        //    (iOS needs user interaction to enable TTS)
        //    if not iOS, continue with preinit
        if(iOS())
        {
            $('#maindiv').hide();
            $('#bigplaybutton').show();
        }
        else 
        {
            $('#bigplaybutton').hide();
            $('#maindiv').show();
            setTimeout(preinit, 500);    
        }
        
        // ugly hack - Reload page in case there is a javascript error - some times there are inconsistencies in the data sent by the server which crashes the site. 
        window.onerror = function() 
        {
         //   location.reload();
        }
        
        
        // Detect if TTS is not supported or if there are no PT voices and show appropriate dialog
        // if all is fine (which currently only happens in macOS >10.10 & iOS), then proceed with init
        function preinit()
        {
            /*if(responsiveVoice.voiceSupport() == 0)// || window.speechSynthesis.getVoices() == 0)
            {
                // no voice, show error
                $('#novoice').show();
                $('#maindiv').hide();
            }else if(isTherePTPTVoice() == false) //no portuguese PT-PT voice
            {
                // no voice, show error
                $('#BRvoice').show();
                $('#maindiv').hide();
            }else*/
                init('Greek Female');
        }
        
        // Update interface with data from server and start the dictation process
        function    init(dictation_language)
        {
            // make sure maindiv is shown and not the BRmessage
            //$('#BRvoice').hide();
            //$('#maindiv').show();
            tts_language = dictation_language;
            
            
            // Setup datepicker to choose parlament sessions
            $( "#escolherData" ).bind( "click", function(){$('#escolherData_div').toggle(duration=500);});
            $.datepicker.setDefaults($.datepicker.regional["pt"]);
            var start = new Date("2010-01-11");
            var end = new Date("2017-03-03");
            $('#escolherData_div').datepicker({yearRange: "2010:2017", dateFormat: 'yy-mm-dd', changeYear: true, changeMonth: true, onSelect: function () {dateSelected();}});
            $('#escolherData_div').datepicker("setDate", 
                                                new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime())));


            // cancel current TTS requests. this will also stop the recursive calling of updateLine
            responsiveVoice.cancel();

            // get data from hidden DOM element generated by php server-side
            // an array of <p>'s, which are the lines we want to read out
            var div = document.getElementById("dom-target_PT");
            items = div.getElementsByTagName("p"); 
            var urlOriginal = items[0].textContent; //first <p> is the link to demo.cracia.org
            $('#linkDemocracia').attr('href',urlOriginal);

            // Find date string in second <p> using regex. parlament data is regular enough that this always seems to work fine
            /*date = items[1].textContent.toLowerCase();
            seleccionado = date.match(/(segunda-feira|terça-feira|quarta-feira|quinta-feira|sexta-feira|sábado).*?\d\d\d\d/);
            seleccionado = seleccionado[0].charAt(0).toUpperCase() + seleccionado[0].slice(1);
            mes = seleccionado.match(/(janeiro|fevereiro|março|abril|maio|junho|julho|agosto|setembro|outubro|novembro|dezembro)/);
            k = mes['index'];
            seleccionado = seleccionado.slice(0,k) + seleccionado[k].toUpperCase() + seleccionado.slice(k+1);*/
            date = items[1].textContent;

            $('#data').text("Ημερομηνία συνεδρίας: "+date);
            
            // Insert lines in the #deixaPT scrollable element for display, clicking. Generate links such that people can refer back to a certain line
            //splitted = urlOriginal.split('/').reverse();
            splitted = date.split('-');
            year = splitted[0];
            month = splitted[1];
            day = splitted[2];
            
            //add all values to deixaPT as divs
            for(u = 0;u < items.length;u++)
            {
                var item = items[u];
                linkstr = './index.el.php?y='+year+
                                                                '&m='+month+
                                                                '&d='+day+
                                                                '&l='+u.toString();
                
                $('#deixaPT').append('<h3 style="font-size:12px;margin:5px;" onclick="scrollToElement(this);" id="h3_' + u.toString() + '">'+item.textContent+'   <a href="'+linkstr+'"><img style="filter:grayscale(100%); display: inline; opacity: 0.3;" src="Sharethis.svg" width=14px"></a></h3>--');
                
                
                //$('#deixaPT').append('<h3 style="font-size:12px;margin:5px;" onclick="scrollToElement(this);" id="h3_' + u.toString() + '">'+item.textContent+'</h3>--');
            }
            
            // Create the wordcloud with the data in the #wordcloud hidden element, generated server side by php
          /*  words = [];
            $('#wordcloud').children().children().toArray().forEach(
                function(currentVal, index, array)
                {
                    words.push({text: currentVal.childNodes[0].textContent,
                                weight: currentVal.childNodes[1].textContent});

                });
            $('#wordcloud2').jQCloud(words,{colors: ["#bd0026", "#e31a1c", "#fc4e2a", "#fd8d3c", "#feb24c"]});
            */
            
            // Start talking
            ndeixas = items.length;
            language = 'el';
            mute = 1;
            setPT();
            muteOff();

            // start reading just before the middle of the session
            var startDeixa = Math.floor(ndeixas/2-50-20);
            
            // if there is an 'l' GET parameter, start reading at that line
            var l = get('l');
            if(l != undefined)
                startDeixa = parseInt(l);
            
            // start reading with updateLine - will recursively speak the session line-by-line
            setTimeout(updateLine(items, startDeixa),0);
            
        } //end init
        
        
        // 1) Update interface to highlight line number ii
        //     Translate line ii to currently selected language using google translate
        //     Speak current line out loud using responsivevoice
        function updateLine(items,ii)
        {
            if(ii >= items.length)
                return;
            var callback = function(){updateLine(items,ii+1);};

            // Get PT data
            var textPT = items[ii].textContent;
            
   
            // Translate PT data to selected language
            var split = $('#lang').val().split(';');
            var lang = split[0];
            var voice = split[1];

            // Syncronous request
            /*
            var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=pt&tl="+lang+"&dt=t&q=" +encodeURI(textPT);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, false ); // false for synchronous request
            xmlHttp.send( null );
            var textEN =  xmlHttp.responseText;
            textENarray = textEN.split("\"");
            var textENparsed = "";
            for(j = 1; j < textENarray.length-2; j+=4)  
            {
                textENparsed = textENparsed + textENarray[j];
            }
            $('#deixaEN').text(textENparsed);*/
            
            var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=el&tl="+lang+"&dt=t&q=" +encodeURI(textPT);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, true ); // true for asynchronous request
            var language_buffer = language;
            xmlHttp.onload = function (e) 
                                {
                                    if (xmlHttp.readyState === 4) 
                                    if (xmlHttp.status === 200) 
                                    {
                                        var textEN =  xmlHttp.responseText;
                                        textENarray = textEN.split("\"");
                                        var textENparsed = "";
                                        for(j = 1; j < textENarray.length-2; j+=4)  
                                        {
                                            textENparsed = textENparsed + textENarray[j];
                                        }
                                        $('#deixaEN').text(textENparsed).attr('style','font-style: normal;');
                                        
                                        if(language_buffer != 'el')
                                            responsiveVoice.speak(textENparsed,voice,{volume:vol, pitch:1,onend:callback});  
                                    } else 
                                    {
                                        console.error(xmlHttp.statusText);
                                    }
                
                                    
                                };
            xmlHttp.send( null );
            $('#deixaEN').text('...traduzindo...').attr('style','font-style: italic;');
            
            
            // Speak it out loud
            vol = 0;
            if(mute == 0)
                vol = 1;

            /* code for syncronous request 
            if(language == 'pt')
                responsiveVoice.speak(items[ii].textContent,tts_language,{volume:vol, rate:1.0, pitch:0.5,onend:callback});
            else 
                responsiveVoice.speak(textENparsed,voice,{volume:vol, pitch:1,onend:callback});  */
            
            //code for asyncronous request 
            if(language == 'el')
                responsiveVoice.speak(items[ii].textContent,tts_language,{volume:vol, rate:1.0, pitch:1,onend:callback});
                //responsiveVoice.speak(greeklish,tts_language,{volume:vol, rate:1.0, pitch:1,onend:callback});
            
            
            // Update interface to highlight current line in PT
            if(ii > 0)
            {
                var ele0 = "#h3_" + (ii-1).toString();   
                $(ele0).css("font-size", "12px");
            }
            var ele = "#h3_" + ii.toString();
            $(ele).css("font-size", "20px");
            
            $('#deixaPT').scrollTo($(ele),1000,{offset: {top:-($('#deixaPT').height()/2 - $(ele).height()/2)}});

        }
        
        // http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
        function get(name)
        {
           if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search))
              return decodeURIComponent(name[1]);
        }
        
        
        // returns true if there are good quality pt-pt voices supported by responsivevoice.js
        function isTherePTPTVoice()
        {
            return true;
            var v = window.speechSynthesis.getVoices();
            var flag= 0 ;
            v.forEach(function(v) 
                      {
                            if(v.lang.toLowerCase() == 'pt-pt')
                            {
                                if(v.name == 'Joana' || v.name == 'Joana Compact' ||
                                    v.name == 'pt-PT' || v.name == 'Portuguese Portugal')
                                        flag = 1;
                            }
                      });   
            if(flag==1)
                return true;
            return false;
        }
        
        // start PT dictation
        function setPT() 
        {
            language='el';
            $("#micPT").css("opacity", 1);
            $("#micEstrangeiro").css("opacity", 0.2);
            mute = 0; 
            responsiveVoice.setVolume(1);
            $("#mute").css("opacity", 0.2);
            
            wordcloudToPT();
        };
        
        // start translated dictation
        function setEN() 
        {
            language='notPT!';
            $("#micPT").css("opacity", 0.2);
            $("#micEstrangeiro").css("opacity", 1);
            mute = 0; 
            responsiveVoice.setVolume(1);
            $("#mute").css("opacity", 0.2);
            
            wordcloudToEN();
        };
        
        
        function muteToggle() 
        {
            if(mute == 0)
                muteOn();
            else
               muteOff();
        };
        
        // voice off. the TTS engine needs to finish current utterance
        function muteOn()
        {
            mute = 1;
            responsiveVoice.setVolume(0);
            $("#mute").css("opacity", 1);
            $("#micPT").css("opacity", 0.2);
            $("#micEstrangeiro").css("opacity", 0.2);
        };
        
        // voice on
        function muteOff()
        {
            mute = 0; 
            responsiveVoice.setVolume(1);
            $("#mute").css("opacity", 0.2);
            if(language == 'el')
                setPT();
            else setEN();
        };
        
        // load youtube video on iframe
        function music1()
        {
            $('#myFrame').attr('src','https://www.youtube.com/embed/vpZCh9iGo-I?rel=0&start=25&end=40&autoplay=1&loop=1');
            $('#myFrame').show();
        };
        
        // load youtube video on iframe
        function music2()
        {           
            $('#myFrame').attr('src','https://www.youtube.com/embed/gw9fKuymA0I?rel=0&start=25&end=40&autoplay=1&loop=1');
            $('#myFrame').show();
        };
        
        // load youtube video on iframe
        function music3()
        {           
            $('#myFrame').attr('src','https://www.youtube.com/embed/vTIIMJ9tUc8?rel=0&start=0&end=5&autoplay=1&loop=1');
            $('#myFrame').show();
        };
        
        // Updates wordcloud to PT
        function wordcloudToPT()
        {   
            $('#wordcloud2').jQCloud('destroy');
            //console.log(words);
            $('#wordcloud2').jQCloud(words,{colors: ["#bd0026", "#e31a1c", "#fc4e2a", "#fd8d3c", "#feb24c"]});
            
        };
        
        // Updates wordcloud to currently selected language
        function wordcloudToEN()
        {   
            var split = $('#lang').val().split(';');
            var lang = split[0];
            var voice = split[1];
            
            console.log(split);
            
            totrans = []
            words.forEach(function(item,index)
                        {
                            totrans = totrans + item.text+',';    
                        });
            console.log(totrans);
            
            var url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=el&tl="+lang+"&dt=t&q=" +encodeURI(totrans);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", url, false ); // false for synchronous request
            xmlHttp.send( null );
            var textEN =  xmlHttp.responseText;
            textENarray = textEN.split("\"");
            var textENparsed = "";
            for(j = 1; j < textENarray.length-2; j+=4)  
            {
                textENparsed = textENparsed + textENarray[j];
            }
            
            trans = textENparsed;
            console.log(trans);
            
            transText = trans.split(',');
            
            translatedWords = []
            words.forEach(function(item,index)
                        {
                            translatedWords.push({text: transText[index], weight: words[index].weight});    
                        });
            
            
            $('#wordcloud2').jQCloud('destroy');
            
            $('#wordcloud2').jQCloud(translatedWords,{colors: ["#bd0026", "#e31a1c", "#fc4e2a", "#fd8d3c", "#feb24c"]});
        };
        
        // read date selected by user refresh page with selected date in datepicker
        function dateSelected()
        {
            responsiveVoice.cancel();
            
            
            var day = $('#escolherData_div').datepicker('getDate').getDate();
            var month = $('#escolherData_div').datepicker('getDate').getMonth()+1;
            var year = $('#escolherData_div').datepicker('getDate').getFullYear();
            
            $('#d').attr('value',day);
            $('#m').attr('value',month);
            $('#y').attr('value',year);
            
            document.getElementById('dateForm').submit()
        }
        
        // return true if in iOS, false if not
        function iOS() 
        {
              var iDevices = [
                'iPad Simulator',
                'iPhone Simulator',
                'iPod Simulator',
                'iPad',
                'iPhone',
                'iPod'
              ];

              if (!!navigator.platform) 
              {
                while (iDevices.length)
                {
                  if (navigator.platform === iDevices.pop()){ return true; }
                }
              }
            //http://stackoverflow.com/questions/9038625/detect-if-device-is-ios
            return false;
        }
        
        
        // When user clicks on big fat play button that only appears on iOS, load the website
        // iOS needs user interaction to do TTS
        function iOSbuttonClick()
        {
            $('#bigplaybutton').hide();
            $('#maindiv').show();
            preinit();
        }
        
        // Sets language to PT
        // scrolls inner scrollbox to clicked element
        // starts TTS and updates translation with text in clicked element
        function scrollToElement(e)
        {
            setPT();
            
            idnum = parseInt(e.id.replace('h3_',''));
            
            var div = document.getElementById("dom-target_PT");
            updateLine(div.getElementsByTagName("p"),idnum);
        }
        

        
    </script>
    
    </body>
</html>
