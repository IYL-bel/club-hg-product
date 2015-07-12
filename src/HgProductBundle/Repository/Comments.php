<?php
/**
 * Club Hg-Product
 *
 * Comments repository
 *
 * @package    HgProductBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace HgProductBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;


/**
 * HgProductBundle\Repository\Comments
 */
class Comments extends EntityRepository
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Application\UsersBundle\Entity\CommentsProduction $commentProduct
     * @return bool
     */
    public function addComment(Request $request, $commentProduct)
    {
        $sql =
            "INSERT INTO comments
                (module, user_name, user_mail, user_site, item_id, text, date, agent, user_ip)
                VALUES ('shop',
                    '" . $commentProduct->getUser()->getFirstName() . ' ' . $commentProduct->getUser()->getLastName() . "',
                    '" . $commentProduct->getUser()->getEmail() . "',
                    'on',
                    '" . $commentProduct->getShopProductsI18nId() . "',
                    '" . $commentProduct->getDescription() . "',
                    '" . $commentProduct->getCreatedAt()->getTimestamp() . "',
                    '" . $request->headers->get('user-agent') . "',
                    '127.0.0.1')";

        $em = $this->getEntityManager();
        $query = $em->getConnection()->prepare($sql);

        return $query->execute();
    }

}
