<?php

namespace App\Controller;

use App\Entity\Robot;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class RobotController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $robots = $this->getDoctrine()->getRepository(Robot::class)->findAll();
        return $this->render('home/index.html.twig', array( 'robots' => $robots ));
    }

    /**
     * @Route("/addRobot", name="addRobot", methods={"POST"})
     */
    public function addRobot( Request $request )
    {
        $robot = new Robot();
        $entitymgr = $this->getDoctrine()->getManager();

        $data = array(
            'name' => $request->request->get('name'),
            'type' => $request->request->get('type'),
            'power' => $request->request->get('power'),
        );
        $robot->setName($data['name']);
        $robot->setType($data['type']);
        $robot->setPower($data['power']);
        
        $entitymgr->persist($robot);
        $entitymgr->flush();

        return new JsonResponse("Saved!", 200);
    }

     /**
     * @Route("/getRobots/{id?}", name="getRobots")
     */
    public function getRobots( Request $request)
    {   
        $id = $request->get('id');
        $robot_array = array();

        if( !$id )
        {
            $robots = $this->getDoctrine()->getRepository(Robot::class)->findAll();
            foreach( $robots as $robot)
            {
                array_push($robot_array, array(
                    'id' => $robot->getId(),
                    'name' => $robot->getName(),
                    'type' => $robot->getType(),
                    'power' => $robot->getPower()
                ));
            }
        }
        else
        {
            $robot = $this->getDoctrine()->getRepository(Robot::class)->find($id);
            return new JsonResponse(array(
                'id' => $robot->getId(),
                'name' => $robot->getName(),
                'type' => $robot->getType(),
                'power' => $robot->getPower()
            ));   
        }

        return new JsonResponse($robot_array);    
    }

    /**
     * @Route("/updateRobot/{!id}", name="updateRobot")
     */
    public function updateRobot( Request $request )
    {   
        $id = $request->get('id');
        $name = $request->get('name');
        $type = $request->get('type');
        $power = $request->get('power');

        $entitymgr = $this->getDoctrine()->getManager();
        $robot = $entitymgr->getRepository(Robot::class)->find($id);

        if(!$robot)
        {
            return new JsonResponse(array(
                'status' => 'Error',
                'message' => 'No robot found with this id! ['. $id . ']'),
            400);
        }

        $robot->setName($name);
        $robot->setType($type);
        $robot->setPower($power);

        $entitymgr->flush();

        return new JsonResponse(array(
            'status' => 'OK',
            'message' => 'Updated!'),
        200);    
    }

    /**
     * @Route("/deleteRobot/{!id}", name="deleteRobot")
     */
    public function destroy( Request $request )
    {
        $entitymgr = $this->getDoctrine()->getManager();

        $robot = $entitymgr->getRepository(Robot::class)->find($request->get('id'));

        $entitymgr->remove($robot);
        $entitymgr->flush();

        return new JsonResponse(array(
            'status' => 'OK',
            'message' => 'Deleted!'),
        200);
    }

    /**
     * @Route("/getStronger/{!id1}/{!id2}", name="getStronger")
     */
    public function getStronger( Request $request )
    {
        $entitymgr = $this->getDoctrine()->getManager();
        $firstRobot = $entitymgr->getRepository(Robot::class)->find($request->get('id1'));
        $secondRobot = $entitymgr->getRepository(Robot::class)->find($request->get('id2'));

        $fitstRobotPower = $firstRobot->getPower();
        $fitstRobotId = $firstRobot->getId();

        $secondRobotPower = $secondRobot->getPower();
        $secondRobotId = $secondRobot->getId();

        if( $fitstRobotPower > $secondRobotPower )
        {
            $stronger = $firstRobot;
        }
        elseif( $fitstRobotPower < $secondRobotPower )
        {
            $stronger = $secondRobot;
        }
        else
        {
            if( $fitstRobotId > $secondRobotId )
            {
                $stronger = $fitstRobot;
            }
            elseif( $fitstRobotId < $secondRobotId )
            {
                $stronger = $secondRobot;
            }
            else
            {
                return new JsonResponse(array(
                    'status' => 'Error',
                    'message' => 'Error occured!'),
                404);
            }
        }

        return new JsonResponse(array(
            'name' => $stronger->getName(),
            'type' => $stronger->getType(),
            'power' => $stronger->getPower()
        ));
        
    }
}
