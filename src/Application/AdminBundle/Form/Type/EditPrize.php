<?php
/**
 * Club Hg-Product
 *
 * Edit Prize form
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Application\AdminBundle\Repository\Prizes as PrizesRepository;

/**
 * Application\AdminBundle\Form\Type\EditPrize
 */
class EditPrize extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'textarea', array(
                'required' => false,
                'label' => 'Название приза',
            ))

            ->add('titleColor', 'choice', array(
                'choices' => array(
                    'blue' => 'Синий',
                    'white' => 'Белый'
                ),
                'required' => false,
                'label' => 'Цвет текста',
                'empty_value' => false,
            ))

            ->add('type', 'choice', array(
                'choices' => PrizesRepository::getNamesType('admin.form.edit_prize.types_label.'),
                'required' => false,
                'label' => 'Уровень приза',
                'empty_value' => ''
            ))

            ->add('awardedScores', 'checkbox', array(
                'required' => false,
                'label' => 'Приз дается за баллы или в розыгрыше',
            ))

            ->add('file', 'file', array(
                'required' => false,
                'label' => 'Картинка приза (формат JPG, PNG, размер 230x284)'
            ))

            ->add('filePath', 'hidden')
            ->add('fileName', 'hidden')
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\AdminBundle\Entity\Prizes',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edit_prize_form';
    }

}
