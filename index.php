<!DOCTYPE html>
<html>
<head>
    <title>Reproduzir Vídeos</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

    <?php
    $path = "./";
    $videoAtual = "";
    if(isset($_GET["Anime"]) && isset($_GET["Ep"])){  
    $msg = ''.$_GET["Anime"].' EP '. $_GET["Ep"];
    echo '<h3 class="titulo">'."M>>".$msg.' </h3>';
    echo '<br>';
    }   
    $ListIgnoreFiles = array('.','.git','LICENSE','README.md','.gitattributes','.gitignore', '..','Player','js', 'script.js', 'index.php', 'node_modules', 'package-lock.json', 'package.json', 'seta-esquerda.png', 'seta.png', 'style.css');
    
    function patterns( $string){
        $pattern = '/S(\d+)Ep(\d+)/';
        preg_match($pattern, $string, $matches);
        $episode = isset($matches[2]) ? $matches[2] : "";
        $resultado =  " Episódio " . $episode;
        return $resultado;
    }
   
    class Anime {
        public $nome;
        public $episodios;

        public function __construct($nome){
            $this->nome = $nome;
            $this->episodios = array();
        }

        public function getNome(){
            return $this->nome;
        }

        public function addEpisodio($episodio) {
            $this->episodios[] = $episodio;
        }
    }

    $ListaAnimes = array();
    $Diretorios = scandir($path);

    foreach ($Diretorios as $diretorio) {
        if (in_array($diretorio, $ListIgnoreFiles)) continue;

        $anime = new Anime($diretorio);

        foreach (scandir($path.$diretorio) as $arquivo) {
            if (in_array($arquivo, $ListIgnoreFiles)) continue;
            $anime->addEpisodio($path.$diretorio.'/'.$arquivo);
        }

        array_push($ListaAnimes, $anime);
    }

  //  $videoAtual = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["Anime"])) {
        $nome = $_GET["Anime"];
        $videoAtual = LoadAnimeURL($nome, $ListaAnimes);
        

       // $msg = $_GET["Anime"].' EP '. GetEpsodeName($videoAtual);
          
    }
    if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["Ep"])){
        $videoAtual = $_GET["Ep"];
       echo '<script>changeVideo(\''.$videoAtual.'\')</script>'; 

    }
    function GetEpsodeName($videoAtual){
        isset($videoAtual) ? $videoAtual : "??";
        //encontrar nome do episodio
        
        
        return $videoAtual;
    
    }
    
    
    function LoadAnimeURL(string $nome, array $ListaAnimes)
    {
        
        foreach ($ListaAnimes as $anime) {
            if ($anime->nome === $nome) {
                $episodios = $anime->episodios;
                 
                
                //se epsodio estiver vasio printar Não existe video nesta pastas
                if (count($episodios) === 0) {
                    print_r("Não existe video nesta pastas");
                }else{
                    
                $videoAtual = $episodios[0];
                
                echo '<div class="video-player-container">';
                echo '<video id="video-player" class="video-player" controls>';
                echo '<source src="'.$videoAtual.'" type="video/mp4">';
                echo '</video>';
                echo '<div class="epsodios">';
                echo '<form action="" method="GET">';
                echo '<select name="episodio" onchange="changeVideo(this.value)">';
                echo '<option value='.$episode.'>'.patterns($videoAtual).'</option>';
               
                if (count($episodios) > 1) {
                    /// se lista de episodios for maior que 1 printar proximos episodios
                    $proximosEpisodios = array_slice($episodios, 1);
                    /// remove primeiro episodio da lista;
                    echo '<div class="seta-esquerda">';
                    echo '<h3>Próximos Episódios:</h3>';
                    echo '<ul>';
                    //////////////////////////////////////////////////////////////////
                            
                    foreach ($proximosEpisodios as $episodio) {
                            
                           
                        echo '<option value="'.$episodio.'">'.patterns($episodio).'</option>';
                        /*
                           
                      /////////////////////////////////////////////////////////////////////     

                        
                        echo '<a href="#video-player"><button type="button" class="proximoEpisodio" onClick=" changeVideo(\''.$episodio.'\')">'.$resultado.'</button></a>';
                        */
                        
                    }
                    echo '</select>';
                    echo '</form>';
                    echo '</ul>';
                    echo '</div>';
                }
                
                echo '</div>';
                return $videoAtual;
            }
            }
        }
    }
    ?>

    <div class="anime-list">
        <?php
        echo '<form action="" method="GET">';
        
        echo '<select name="Anime" onchange="this.form.submit()">';
        echo  '<option value="--">'."---Selecione um anime---".'</option>';
        foreach ($ListaAnimes as $anime) {
            echo  '<option value="'.$anime->nome.'">'.$anime->nome.'</option>';
          
        }
       // echo '<button type="submit" name="Pasta" value="'.$anime->nome.'">'.$anime->nome.'</button>';
            echo '</form>';
        ?>
    </div>

    <script>
        function changeVideo(videoUrl) {
            const videoPlayer = document.getElementById("video-player");
            videoPlayer.src = videoUrl;
            videoPlayer.load();
            videoPlayer.play();
        }
    </script>
</body>
</html>
