<?php

namespace TresEnRayaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TresEnRayaBundle\Entity\Partida;
use \Doctrine\ORM\ORMException;

class TresEnRayaController extends Controller
{
    // private $board = [[0,0,0],[0,0,0],[0,0,0]];
    private $board = [['-','-','-'],['-','-','-'],['-','-','-']];
    private $lastMoved = 'x';
    private $movements = 0;
    // private $winnerCells = [];
    
    /**
     * Set initial state of the board and the game
     */
    public function indexAction()
    {        
        try{
            $entityManager = $this->getDoctrine()->getManager();   
            
            // If there is a game (partida) not finished, get it to continue the game    
            //  $partidaStarted = $this->getPartidaStarted($entityManager)[0];         //no tengo aqui en cuenta que puede no haber partidas sin acabar, entonces devuelve vacio...
            $partidaStartedArray = $this->getPartidaStarted($entityManager);            
            if(!empty($partidaStartedArray) && count($partidaStartedArray)>0){
                $partidaStarted = $partidaStartedArray[0];
                $this->board = json_decode($partidaStarted->getBoard()); 
                $this->lastMoved =  $partidaStarted->getLastMoved();
                $this->movements = $partidaStarted->getMovements();
            }

            // Render the board
            return $this->render('@TresEnRaya/Default/index.html.twig', array(
                'board'=>$this->board,
                'lastMoved'=>$this->lastMoved,
                'movements'=>$this->movements,
                'winner'=>$this->winner(),
            ));

            /* return new Response(
            '<html><body><p>Partida elegida: ' . var_dump(json_decode($partidaStarted->getBoard())) . '</p></body></html>'
        ); */

        } catch (ORMException $e) {
            var_dump( $e->getMessage());
        }          
        
    }

    /**
     * Pass new movement of the player to the active board
     */
    public function moveAction( $player = null, $position = null){
        try{

            $positionArray = explode("-",$position);
            $entityManager = $this->getDoctrine()->getManager();

            // If there is a game (partida) not finished, get it to continue the game    
            //$partidaStarted = $this->getPartidaStarted($entityManager)[0];
            $partidaStartedArray = $this->getPartidaStarted($entityManager);       
            $teo = ''     ;
            if(!empty($partidaStartedArray) && count($partidaStartedArray)>0){
                $partidaStarted = $partidaStartedArray[0];
                // Get board already started
                $this->board = json_decode($partidaStarted->getBoard()); 
                // Write the 'x' or 'o' in the postion of the movement
                $this->board[$positionArray[0]][$positionArray[1]] = $player;     
                // Change the last player that has moved
                $this->lastMoved =  $player;
                if($this->lastMoved === 'x'){
                    $this->lastMoved = 'o';                   
                }elseif ($this->lastMoved === 'o'){
                    $this->lastMoved = 'x';  
                } 
                // Incremet number of movements
                $this->movements = $partidaStarted->getMovements()+1;

                // Update the game (Partida) already started
                $partidaStarted->setBoard(json_encode($this->board));
                $partidaStarted->setLastMoved($this->lastMoved);
                $partidaStarted->setMovements($this->movements);
                // tell Doctrine you want to (eventually) save the Partida (no queries yet)
                $entityManager->persist($partidaStarted);   

            } else{
                // Create a new game (Partida) with the first movement in it
                // Write the 'x' or 'o' in the postion of the movement
                $this->board[$positionArray[0]][$positionArray[1]] = $player;   
                $partidaNew = new Partida();
                $boardJSON = json_encode(array_values($this->board));
                $partidaNew->setBoard($boardJSON);
                $partidaNew->setFinished(0);
                $partidaNew->setLastMoved('o');
                $partidaNew->setMovements(1);
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($partidaNew);

            }
            
            // actually executes the queries
            $entityManager->flush();

        } catch (ORMException $e) {
            var_dump( $e->getMessage());
        }  

        return $this->redirectToRoute('tresenraya_home');

        /* return new Response(
            '<html><body><p>Partida elegida: ' . $teo . var_dump($partidaStartedArray) . '</p></body></html>'
        ); */

    }

    /**
     * Restart the game
     */
    public function restartAction(){

        try{
            $entityManager = $this->getDoctrine()->getManager();
            // $partidaStarted = $this->getPartidaStarted($entityManager)[0];      
            $partidaStartedArray = $this->getPartidaStarted($entityManager);            
            if(!empty($partidaStartedArray) && count($partidaStartedArray)>0){
                $partidaStarted = $partidaStartedArray[0];
                $partidaStarted->setFinished(1);
                $entityManager->persist($partidaStarted);
                $entityManager->flush(); 
            }                  
            
        } catch (ORMException $e) {
            var_dump( $e->getMessage());
        }  

        return $this->redirectToRoute('tresenraya_home');
    }

    /**
     * Get Partida not finished
     */
    private function getPartidaStarted($em){
        $entidadPartida = $em->getRepository("TresEnRayaBundle:Partida");
        
        return $entidadPartida->findBy(
            array(
                "finished" => 0
            )
        ) ? $entidadPartida->findBy(array("finished" => 0)) : []; 
    }

    /**
     * Know if there is a winner with the last movement
     */
    private function winner(){
        $board = $this->board;
        $winnerVars = array();

        //linea horizontal arriba          
        if($board[0][0]!='-' && $board[0][0]==$board[0][1] && $board[0][1]==$board[0][2]){
            $winnerCells = ['00','01','02'];
            if($board[0][0] == 'x'){   
                $winnerVars[0] = 1;
                $winnerVars[1] = ['00','01','02'];
                return $winnerVars;
            }
            if($board[0][0] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['00','01','02'];
                return $winnerVars;                
            }
        }
        
        //linea horizontal medio      
        if($board[1][0]!='-' && $board[1][0]==$board[1][1] && $board[1][1]==$board[1][2]){
            if($board[1][0] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['10','11','12'];
                return $winnerVars;
            }
            if($board[1][0] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['10','11','12'];
                return $winnerVars;                
            }
        }
        
        //linea horizontal abajo 
        if($board[2][0]!='-' && $board[2][0]==$board[2][1] && $board[2][1]==$board[2][2]){
            if($board[2][0] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['20','21','22'];
                return $winnerVars;
            }
            if($board[2][0] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['20','21','22'];
                return $winnerVars;                
            }
        }

        //linea vertical izquierda       
        if($board[0][0]!='-' && $board[0][0]==$board[1][0] && $board[1][0]==$board[2][0]){
            if($board[0][0] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['00','10','20'];
                return $winnerVars;
            }
            if($board[0][0] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['00','10','20'];
                return $winnerVars;                
            }
        }

        //linea vertical medio        
        if($board[0][1]!='-' && $board[0][1]==$board[1][1] && $board[1][1]==$board[2][1]){
            if($board[0][1] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['01','11','21'];
                return $winnerVars;
            }
            if($board[0][1] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['01','11','21'];
                return $winnerVars;                
            }
        }

        //linea vertical derecha      
        if($board[0][2]!='-' && $board[0][2]==$board[1][2] && $board[1][2]==$board[2][2]){
            if($board[0][2] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['02','12','22'];
                return $winnerVars;
            }
            if($board[0][2] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['02','12','22'];
                return $winnerVars;                
            }
        }
        
        //linea diagonal 1        
        if($board[0][0]!='-' && $board[0][0]==$board[1][1] && $board[1][1]==$board[2][2]){
            if($board[0][0] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['00','11','22'];
                return $winnerVars;
            }
            if($board[0][0] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['00','11','22'];
                return $winnerVars;                
            }
        }

        //linea diagonal 2        
        if($board[0][2]!='-' && $board[0][2]==$board[1][1] && $board[1][1]==$board[2][0]){
            if($board[0][2] == 'x'){
                $winnerVars[0] = 1;
                $winnerVars[1] = ['02','11','20'];
                return $winnerVars;
            }
            if($board[0][2] == 'o'){
                $winnerVars[0] = 2;
                $winnerVars[1] = ['02','11','20'];
                return $winnerVars;                
            }  
        } 
        
        $winnerVars[0] = -1;
        $winnerVars[1] = ['kk','kk','kk'];
        return $winnerVars;
        
    }
}