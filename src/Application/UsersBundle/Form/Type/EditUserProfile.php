<?php
/**
 * Club Hg-Product
 *
 * Edit User Profile form
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Application\UsersBundle\Form\Type\EditUserProfile
 */
class EditUserProfile extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array(
                'required' => false,
                'label' => 'Имя'
            ))

            ->add('lastName', 'text', array(
                'required' => false,
                'label' => 'Фамилия'
            ))

            ->add('birthday', 'date', array(
                'required' => false,
                'label' => 'День рождения',
                'years' => range(date('Y'), date('Y') - 80),
            ))

            ->add('occupation', 'text', array(
                'required' => false,
                'label' => 'Род занятий',
            ))

            ->add('postcode', 'text', array(
                'required' => false,
                'label' => 'Индекс',
            ))

            ->add('shippingAddress', 'text', array(
                'required' => false,
                'label' => 'Адрес доставки',
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\Users',
            'validation_groups' => array('edit-profile'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edit_user_profile_form';
    }

}
