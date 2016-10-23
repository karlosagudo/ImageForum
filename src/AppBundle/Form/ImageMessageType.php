<?php
/**
 * Created by PhpStorm.
 * User: carlosagudobelloso
 * Date: 23/10/16
 * Time: 11:26.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ImageMessageType extends AbstractType
{
    private $parameters;

    /**
     * @param $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => false])
            ->add('image', FileType::class, [
                'required' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => $this->parameters['max_file_size'],
                        'mimeTypes' => $this->parameters['mime_types'],
                        'maxWidth' => $this->parameters['max_width'],
                        'maxHeight' => $this->parameters['max_height'],
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ImageMessage',
        ));
    }
}
