<?php
/**
 * Club Hg-Product
 *
 * Add Check File form
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Application\UsersBundle\Form\Type\AddCheckFile
 */
class AddCheckFile extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'required' => false,
            ))
            ->add('filePath', 'hidden')
            ->add('fileName', 'hidden')
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_check_file_form';
    }

}
