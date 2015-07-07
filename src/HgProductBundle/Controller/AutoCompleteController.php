<?php
/**
 * Club Hg-Product
 *
 * Auto Complete controller
 *
 * @package    HgProductBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace HgProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * HgProductBundle\Controller\AutoCompleteController
 */
class AutoCompleteController extends Controller
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductAction(Request $request)
    {
        $value = $request->get('term');
        $json = array();

        if ( iconv_strlen($value, 'UTF-8') >= 2 ) {
            /** @var $emHgProd \Doctrine\ORM\EntityManager */
            $emHgProd = $this->getDoctrine()->getManager('hg_prod_ru');

            /** @var $shopProductI18nRepository \HgProductBundle\Repository\ShopProductsI18n */
            $shopProductI18nRepository = $emHgProd->getRepository('HgProductBundle:ShopProductsI18n');
            $itemsProducts = $shopProductI18nRepository->getLikeName($value);

            /** @var $item \HgProductBundle\Entity\ShopProductsI18n */
            foreach ($itemsProducts as $item) {
                $json[] = array(
                    'label' => $item['name'],
                    'value' => $item['id'],
                );
            }
        }

        $response = new Response();
        $response->setContent(json_encode($json));

        return $response;
    }

}
