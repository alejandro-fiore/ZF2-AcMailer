<?php
namespace AcMailer\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\View\Model\ViewModel;

/**
 * Template specific options
 * @author Alejandro Celaya Alastrué
 * @link http://www.alejandrocelaya.com
 */
class TemplateOptions extends AbstractOptions implements ViewModelConvertibleInterface
{
    /**
     * @var bool
     */
    protected $useTemplate = false;
    /**
     * @var string
     */
    protected $path = 'ac-mailer/mail-templates/mail';
    /**
     * @var array
     */
    protected $params = array();
    /**
     * @var array
     */
    protected $children = array();

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $useTemplate
     * @return $this
     */
    public function setUseTemplate($useTemplate)
    {
        $this->useTemplate = $useTemplate;
        return $this;
    }
    /**
     * @return boolean
     */
    public function getUseTemplate()
    {
        return $this->useTemplate;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function setChildren($children)
    {
        $children         = (array) $children;
        $this->children   = array();
        // Cast each child to a TemplateOptions object
        foreach ($children as $captureTo => $child) {
            $this->children[$captureTo] = new TemplateOptions($child);
            // Recursively add childs
            if (array_key_exists('children', $child)) {
                $this->children[$captureTo]->setChildren($child['children']);
            }
        }

        return $this;
    }
    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return ViewModel
     */
    public function toViewModel()
    {
        // Create the base ViewModel
        $model = new ViewModel($this->getParams());
        $model->setTemplate($this->getPath());

        // Add childs recursively
        /* @var TemplateOptions $child */
        foreach ($this->getChildren() as $captureTo => $child) {
            $model->addChild($child->toViewModel(), $captureTo);
        }

        return $model;
    }
}
