<?php
        //FUNÇÃO PARA SUBIR IMAGEM
        function enviaImagem($imagem, $caminho, $imagemTemp){
            $extensao = pathinfo($imagem, PATHINFO_EXTENSION);
            $extensao = strtolower($extensao);

            if(strstr('.jpg;.jpeg;.png', $extensao)){
                $imagem = $caminho.mt_rand().".".$extensao;

                $diretorio = "imagens/".$caminho."/";

                move_uploaded_file($imagemTemp, $diretorio.$imagem);
            }else{ ?>
                <div class="alert alert-danger" role="alert">
                    A imagem deve ser no formato *.jpeg, *jpg e *.png!
                </div>
            <?php
            }
            return $imagem;
        }
      ?>