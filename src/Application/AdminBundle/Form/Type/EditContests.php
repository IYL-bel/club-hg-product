<?php
/**
 * Club Hg-Product
 *
 * Edit Contests form
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Application\AdminBundle\Form\Type\EditContests
 */
class EditContests extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => false,
                'label' => 'Название конкурса',
            ))

            ->add('description', 'textarea', array(
                'required' => false,
                'label' => 'Описание конкурса',
            ))

            ->add('pointsParticipation','text', array(
                'required' => false,
                'label' => 'Количество баллов за участие'
            ))

            ->add('pointsWinner','text', array(
                'required' => false,
                'label' => 'Количество баллов за победу'
            ))

            ->add('file', 'file', array(
                'required' => false,
                'label' => 'Картинка конкурса (формат JPG, PNG, размер 231x290)'
            ))

            ->add('filePath', 'hidden')
            ->add('fileName', 'hidden')

            ->add('startedAt', 'date', array(
                'required' => false,
                'label' => 'Начало конкурса',
            ))

            ->add('finishedAt', 'date', array(
                'required' => false,
                'label' => 'Окончание конкурса',
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\AdminBundle\Entity\Contests',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edit_contests_form';
    }

}
