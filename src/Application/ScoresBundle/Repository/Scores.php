<?php
/**
 * Club Hg-Product
 *
 * Scores repository
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\ScoresBundle\Repository\Scores
 */
class Scores extends EntityRepository
{

    const TYPE__CONTESTS_PARTICIPATION       = 1;
    const TYPE__CONTESTS_WINNER              = 2;
    const TYPE__CONTESTS_PARTICIPATION_BASE  = 3;
    const TYPE__CONTESTS_WINNER_BASE         = 4;
    const TYPE__REGISTRATION                 = 11; // Регистрация в клубе
    const TYPE__PRIZES                       = 20;
    const TYPE__CHECKS                       = 22;
    const TYPE__CHECKS_BASE                  = 23; // Загрузка чека в личный кабинет (по умолчанию)
    const TYPE__FILLED_PROFILE               = 33; // Заполнение профиля "о себе"
    const TYPE__FILLING_QUESTIONNAIRE        = 34; // Заполнение расширенной анкеты
    const TYPE__TEST_DRIVE_REQUEST_BASE      = 41; // Заявка на тест-драйв
    const TYPE__TEST_DRIVE_REPORT_BASE       = 42; // Отчет о тест-драйве
    const TYPE__REVIEWS_PRODUCT_BASE         = 46; // Написание отзыва на продукт
    const TYPE__SHARE_FB                     = 51; // Поделиться ссылкой в Facebook
    const TYPE__SHARE_VK                     = 52; // Поделиться ссылкой Вконтакте
    const TYPE__SHARE_OK                     = 53; // Поделиться ссылкой в Одноклассники
    const TYPE__FB_REFERRAL_ACTIVITY         = 61;
    const TYPE__VK_REFERRAL_ACTIVITY         = 62;
    const TYPE__OK_REFERRAL_ACTIVITY         = 63;


    /**
     * @param null|string $name
     * @return array|int
     */
    public static function getNameTypes($name = null)
    {
        $types = array(
            'registration'                 => self::TYPE__REGISTRATION,
            'share_fb'                     => self::TYPE__SHARE_FB,
            'share_vk'                     => self::TYPE__SHARE_VK,
            'share_ok'                     => self::TYPE__SHARE_OK,
            'contests_participation_base'  => self::TYPE__CONTESTS_PARTICIPATION_BASE,
            'contests_winner_base'         => self::TYPE__CONTESTS_WINNER_BASE,
            'checks'                       => self::TYPE__CHECKS,
            'checks_base'                  => self::TYPE__CHECKS_BASE,
            'filled_profile'               => self::TYPE__FILLED_PROFILE,
            'test_drive_request_base'      => self::TYPE__TEST_DRIVE_REQUEST_BASE,
            'test_drive_report_base'       => self::TYPE__TEST_DRIVE_REPORT_BASE,
            'reviews_product_base'         => self::TYPE__REVIEWS_PRODUCT_BASE,
            'filling_questionnaire'        => self::TYPE__FILLING_QUESTIONNAIRE,
        );

        if ($name) {
            $allNameTypes = array_keys($types);
            if ( in_array($name, $allNameTypes) ) {
                return $types[$name];
            }
        }

        return $types;
    }

    /**
     * @param array $types
     * @return array
     */
    public function getScoresForTypes($types)
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->select(array('s.id', 's.type', 's.points'))
            ->where( $qb->expr()->in('s.type', $types) )
        ;

        return $qb->getQuery()->getResult();
    }

}
