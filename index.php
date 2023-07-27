<!DOCTYPE html>
<html>
<head>
    <title>Reproduzir Vídeos</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <?php
    $path = "./";
    $ListIgnoreFiles = array('.','.git','LICENSE','README.md','.gitattributes','.gitignore', '..','Player','js', 'script.js', 'index.php', 'node_modules', 'package-lock.json', 'package.json', 'seta-esquerda.png', 'seta.png', 'style.css');
    

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

    $videoAtual = "";
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["Pasta"])) {
        $nome = $_GET["Pasta"];
        $videoAtual = LoadAnimeURL($nome, $ListaAnimes);
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
                  
                if (count($episodios) > 1) {
                    $proximosEpisodios = array_slice($episodios, 1);
                    echo '<div class="seta-esquerda">';
                    echo '<h3>Próximos Episódios:</h3>';
                    echo '<ul>';
                            echo '<div class="epsodios">';
                    foreach ($proximosEpisodios as $episodio) {
                           $temp = $episodio;
                           $pattern = '/S(\d+)Ep(\d+)/';
                           preg_match($pattern, $temp, $matches);
                           $season = isset($matches[1]) ? $matches[1] : "";
                           $episode = isset($matches[2]) ? $matches[2] : "";
                           $resultado = "Temporada" . $season . " Episódio " . $episode;
                           

                        
                        echo '<button type="button" class="proximoEpisodio" onClick="changeVideo(\''.$episodio.'\')">'.$resultado.'</button>';
                          
                        
                    }
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
        foreach ($ListaAnimes as $anime) {
            echo '<form action="" method="GET">';
            echo '<button type="submit" name="Pasta" value="'.$anime->nome.'">'.$anime->nome.'</button>';
            echo '</form>';
        }
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
