<?php
/**
 * Club Hg-Product
 *
 * Statuses Manager manager
 *
 * @package    TemplatesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use TemplatesBundle\Repository\Statuses as StatusesRepository;
use Application\UsersBundle\Entity\Users;


/**
 * TemplatesBundle\Manager\StatusesManager
 */
class StatusesManager
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;


    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getActualStatuses()
    {
        $defaultStatuses = StatusesRepository::getDefaultScoresForStatuses();
        $actualStatuses = array();

        /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
        $translator = $this->container->get('translator');

        foreach ($defaultStatuses as $key => $defaultStatus) {
            $actualStatuses[$key] = array(
                'scores' => $defaultStatus,
                'description' => $translator->trans('templates.description.statuses.' . $key)
            );
        }

        $statusesRepository = $this->em->getRepository('TemplatesBundle:Statuses');
        $statuses = $statusesRepository->findAll();

        if ($statuses) {
            /** @var $status \TemplatesBundle\Entity\Statuses */
            foreach ($statuses as $status) {
                $defaultStatuses[$status->getNameStatus()] = array(
                    'scores' => $status->getScores(),
                    'description' => $status->getDescription(),
                );
            }
        }

        return $actualStatuses;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return int|string
     */
    public function getUserStatus($user)
    {
        $nameStatus = null;
        $actualStatuses = $this->getActualStatuses();

        foreach ($actualStatuses as $key => $status) {
            if ($user->getScorePoints() >= $status['scores']) {
                $nameStatus = $key;
            }
        }

        return $nameStatus;
    }

}
