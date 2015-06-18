<?php
/**
 * Club Hg-Product
 *
 * Default controller
 *
 * @package    ApplicationBaseBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\Query\ResultSetMapping;


/**
 * Application\BaseBundle\Controller\DefaultController
 */
class DefaultController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('application_base_security_login'));
        }

        // MAIN TIPS CLUB
        $cautionMainTips = false;
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $mainTipsClubRepository \TemplatesBundle\Repository\MainTipsClub */
        $mainTipsClubRepository = $em->getRepository('TemplatesBundle:MainTipsClub');
        $mainTipsClub[1] = $mainTipsClubRepository->findOneBy( array('numTip' => 1) );
        $mainTipsClub[2] = $mainTipsClubRepository->findOneBy( array('numTip' => 2) );

        if ($mainTipsClub[1] &&  $mainTipsClub[2]) {
            $cautionMainTips = true;
        }

        return array(
            'main_tips_club' => $mainTipsClub,
            'show_main_tips_club' => $cautionMainTips,
        );
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function contestsAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\AdminBundle\Repository\Prizes */
        $contestsRepository = $em->getRepository('ApplicationAdminBundle:Contests');

        $contests = $contestsRepository->findBy( array(), array('createdAt' => 'DESC') );

        return array(
            'contests' => $contests
        );
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function prizesAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\AdminBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationAdminBundle:Prizes');

        $prizes = $prizesRepository->findBy( array(), array('createdAt' => 'DESC') );

        return array(
            'prizes' => $prizes
        );
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function catalogProductsAction()
    {
        return array();
    }








    public function testAction()
    {


        /** @var $stmt \Doctrine\ORM\EntityManager */
        $stmt = $this->get('doctrine')->getManager('hg_prod_ru')
            ->getConnection()
            ->prepare('SELECT c.id, c.title, c.prev_text FROM content c WHERE id = :id');
        $stmt->bindValue('id', 184);
        $stmt->execute();
        $re = $stmt->fetchAll();

        var_dump($re);



        return array();
    }

}
