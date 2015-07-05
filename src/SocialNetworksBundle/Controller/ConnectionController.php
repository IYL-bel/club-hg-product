<?php
/**
 * Club Hg-Product
 *
 * Connection controller
 *
 * @package    SocialNetworksBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace SocialNetworksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Application\ContestsBundle\Entity\ContestsVoting;
use Application\ContestsBundle\Repository\ContestsVoting as ContestsVotingRepository;


/**
 * SocialNetworksBundle\Controller\ConnectionController
 */
class ConnectionController extends Controller
{

    /**
     * @Template()
     *
     * @param string $url
     * @return array
     */
    public function sharingAction($url)
    {
        /** @var $socialNetworksService \SocialNetworksBundle\Service\SocialNetworksService */
        $socialNetworksService = $this->get('social_networks.service');

        // FACEBOOK SHARE LINK
        $fbRedirect = $this->generateUrl('social_networks_connection_after_share_fb', array(), true);
        $fbLink = $socialNetworksService->getSharingFb($url, $fbRedirect);

        // VKONTAKTE SHARE LINK
        $vkLink = $socialNetworksService->getSharingVk($url);

        //ODNOKLASSNIKI SHARE LINK
        $okLink = $socialNetworksService->getSharingOk($url);

        $url = array(
            'fb' => $fbLink,
            'vk' => $vkLink,
            'ok' => $okLink,
        );

        return array(
            'url' => $url,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function afterShareFbAction(Request $request)
    {
        $sharing = false;
        $errorCode = $request->query->get('error_code');
        $errorMessage = $request->query->get('error_message');

        if (!$errorMessage) {
            $sharing = true;
        }

        if ($errorCode == 100 && $request->getHost() == 'virtual.club-hg-product' ) {
            $sharing = true;
        }

        if ($sharing) {
            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addShareScore($this->getUser(), 'fb');
        }

        return array(
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        );
    }

    /**
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addShareScoreAction($type)
    {
        $success = true;
        if ($type == 'vk' || $type == 'ok') {

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addShareScore($this->getUser(), $type);
        }

        return new Response(json_encode(array(
            'success' => $success,
        )));
    }

    /**
     * @Template()
     *
     * @param string $url
     * @param int $idMember
     * @return array
     */
    public function shareForContestsVotingAction($url, $idMember)
    {
        /** @var $socialNetworksService \SocialNetworksBundle\Service\SocialNetworksService */
        $socialNetworksService = $this->get('social_networks.service');
        $fbRedirect = $this->generateUrl('social_networks_connection_share_add_vote_contests_fb', array('idMember' => $idMember), true);
        $link['fb'] = $socialNetworksService->getSharingFb($url, $fbRedirect);
        $link['vk'] = $socialNetworksService->getSharingVk($url);
        $link['ok'] = $socialNetworksService->getSharingOk($url);

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $contestsVotingRepository \Application\ContestsBundle\Repository\ContestsVoting */
        $contestsVotingRepository = $em->getRepository('ApplicationContestsBundle:ContestsVoting');

        $count['fb'] = $contestsVotingRepository->getCountVoteForType(ContestsVotingRepository::TYPE_FB, $idMember);
        $count['vk'] = $contestsVotingRepository->getCountVoteForType(ContestsVotingRepository::TYPE_VK, $idMember);
        $count['ok'] = $contestsVotingRepository->getCountVoteForType(ContestsVotingRepository::TYPE_OK, $idMember);

        return array(
            'url' => $link,
            'value' => $count,
            'idMember' => $idMember,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $idMember
     * @return array
     */
    public function shareAddVoteContestsForFbAction(Request $request, $idMember)
    {
        $sharing = false;
        $errorCode = $request->query->get('error_code');
        $errorMessage = $request->query->get('error_message');

        if (!$errorMessage) {
            $sharing = true;
        }

        if ($errorCode == 100 && $request->getHost() == 'virtual.club-hg-product' ) {
            $sharing = true;
        }

        if ($sharing) {
            $this->addVoteContests('fb', $idMember);
        }

        return array(
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        );
    }

    /**
     * @param string $type
     * @param int $idMember
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function shareAddVoteContestsAction($type, $idMember)
    {
        $success = true;
        if ($type == 'vk' || $type == 'ok') {
            $this->addVoteContests($type, $idMember);
        }

        return new Response(json_encode(array(
            'success' => $success,
        )));
    }

    /**
     * @param string $type
     * @param int $idMember
     * @throws \Exception
     * @return bool
     */
    protected function addVoteContests($type, $idMember)
    {
        switch ($type) {
            case 'fb':
                $voteType = ContestsVotingRepository::TYPE_FB;
                break;

            case 'vk':
                $voteType = ContestsVotingRepository::TYPE_VK;
                break;

            case 'ok':
                $voteType = ContestsVotingRepository::TYPE_OK;
                break;

            default:
                throw new \Exception('Set the wrong type of social network');
                break;
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $contestsMembersRepository \Application\ContestsBundle\Repository\ContestsMembers */
        $contestsMembersRepository = $em->getRepository('ApplicationContestsBundle:ContestsMembers');
        /** @var $contestsMember \Application\ContestsBundle\Entity\ContestsMembers */
        $contestsMember = $contestsMembersRepository->find($idMember);

        /** @var $contestsVotingRepository \Application\ContestsBundle\Repository\ContestsVoting */
        $contestsVotingRepository = $em->getRepository('ApplicationContestsBundle:ContestsVoting');
        /** @var $contestsVoting \Application\ContestsBundle\Entity\ContestsVoting */
        $contestsVoting = $contestsVotingRepository->findOneBy( array(
            'user' => $this->getUser(),
            'contestsMember' => $contestsMember,
            'type' => $voteType,
        ) );

        if (!$contestsVoting) {
            $contestsVoting = new ContestsVoting();
            $contestsVoting->setUser( $this->getUser() );
            $contestsVoting->setContestsMember($contestsMember);
            $contestsVoting->setType($voteType);

            $em->persist($contestsVoting);
            $em->flush();

            return true;
        }

        return false;
    }

}
