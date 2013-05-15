<?php

namespace Spicy\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Spicy\SiteBundle\Entity\Video;
use Spicy\SiteBundle\Form\VideoType;
use Spicy\SiteBundle\Entity\TypeVideo;
use Spicy\SiteBundle\Form\TypeVideoType;
use Spicy\SiteBundle\Entity\GenreMusical;
use Spicy\SiteBundle\Form\GenreMusicalType;
use Spicy\SiteBundle\Entity\Artiste;
use Spicy\SiteBundle\Form\ArtisteType;


class AdminController extends Controller
{
    public function indexAction()
    {
        $video=new Video;
        $video->setTitre('une video');
        $video->setUrl('nIGSG0PZzjA');

        
        return $this->render('SpicySiteBundle:Admin:index.html.twig',array(
            'video'=>$video
        ));
    }
    
    public function homeVideoAction()
    {
       $videos = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->findAll();
       
       /*if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
          }*/
       
       return $this->render('SpicySiteBundle:Admin:homeVideo.html.twig',array(
            'videos'=>$videos
        ));
    }
    
    public function addVideoAction()
    {
        $video=new Video;
        
        $form= $this->createForm(new VideoType,$video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Video bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addVideo.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateVideoAction($id)
    {       
        $video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Video')
                ->find($id);
        
        if ($video == null) {
            throw $this->createNotFoundException('Video inexistant');
        }
        
        $form= $this->createForm(new VideoType,$video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Vidéo bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateVideo.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteVideoAction(Video $video)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Video bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteVideo.html.twig',array(
            'form'=>$form->createView(),
            'artiste'=>$video
        ));
    }
    
    public function homeArtisteAction()
    {
       $artistes = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->findAll();
       
       /*if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
          }*/
       
       return $this->render('SpicySiteBundle:Admin:homeArtiste.html.twig',array(
            'artistes'=>$artistes
        ));
    }
    
    public function addArtisteAction()
    {
        $artiste=new Artiste;
        
        $form= $this->createForm(new ArtisteType,$artiste);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addArtiste.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateArtisteAction($id)
    {       
        $artiste=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:Artiste')
                ->find($id);
        
        if ($artiste == null) {
            throw $this->createNotFoundException('Artiste inexistant');
        }
        
        $form= $this->createForm(new ArtisteType,$artiste);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateArtiste.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteArtisteAction(Artiste $artiste)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($artiste);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Artiste bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_artiste'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteArtiste.html.twig',array(
            'form'=>$form->createView(),
            'artiste'=>$artiste
        ));
    }
    
    public function homeType_videoAction()
    {
       $types = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:TypeVideo')
                ->findAll();
       
       /*if ($types == null) {
            throw $this->createNotFoundException('Types inexistant');
          }*/
       
       return $this->render('SpicySiteBundle:Admin:homeType_video.html.twig',array(
            'types'=>$types
        ));
    }
    
    public function addType_videoAction()
    {
        $type_video=new TypeVideo;
        
        $form= $this->createForm(new TypeVideoType,$type_video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addType_video.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateType_videoAction($id)
    {       
        $type_video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:TypeVideo')
                ->find($id);
        
        if ($type_video == null) {
            throw $this->createNotFoundException('Type inexistant');
        }
        
        $form= $this->createForm(new TypeVideoType,$type_video);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateType_video.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteType_videoAction(TypeVideo $type_video)
    {
        /*$type_video=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:TypeVideo')
                ->find($id);
        
        if ($type_video == null) {
            throw $this->createNotFoundException('Type inexistant');
        }
        
        $form= $this->createForm(new TypeVideoType,$type_video);*/
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($type_video);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Type bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_type_video'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteType_video.html.twig',array(
            'form'=>$form->createView(),
            'type'=>$type_video
        ));
    }
    
    public function homeGenre_musicalAction()
    {
       $genres = $this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->findAll();
       
       /*if ($genres == null) {
            throw $this->createNotFoundException('Genres inexistant');
          }*/
       
       return $this->render('SpicySiteBundle:Admin:homeGenre_musical.html.twig',array(
            'genres'=>$genres
        ));
    }
    
    public function addGenre_musicalAction()
    {
        $genre_musical=new GenreMusical;
        
        $form= $this->createForm(new GenreMusicalType,$genre_musical);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien ajouté');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:addGenre_musical.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function updateGenre_musicalAction($id)
    {       
        $genre_musical=$this->getDoctrine()
                ->getManager()
                ->getRepository('SpicySiteBundle:GenreMusical')
                ->find($id);
        
        if ($genre_musical == null) {
            throw $this->createNotFoundException('Genre inexistant');
        }
        
        $form= $this->createForm(new GenreMusicalType,$genre_musical);
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien modifié');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:updateGenre_musical.html.twig',array(
            'form'=>$form->createView()
        ));
    }
    
    public function deleteGenre_musicalAction(GenreMusical $genre_musical)
    {
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
          $form->bind($request);

          if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($genre_musical);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('info','Genre bien supprimé');
            
            return $this->redirect($this->generateUrl('spicy_admin_home_genre_musical'));
          }
        }
        
        return $this->render('SpicySiteBundle:Admin:deleteGenre_musical.html.twig',array(
            'form'=>$form->createView(),
            'genre'=>$genre_musical
        ));
    }
}
