<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* resource_type/show.html */
class __TwigTemplate_e4c1f211a328885dc05aa3caed0deb32e90a01b14f3f2df3ce3d191eeb8782f2 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "resource_type/show.html"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "resource_type/show.html"));

        // line 1
        echo "<div class=\"modal\" id=\"infos-resource-type-modal\">
    <div class=\"modal-dialog\">
      <div class=\"modal-content\">
        <div class=\"modal-header\">
<div id=\"show-user\">

    <h1> Informations du type de ressource </h1>

    <table class=\"table\">
        <tbody>
            <tr>
                <th>Id</th>
                <td id=\"resource-type-id\"> </td>
            </tr>
            <tr>
                <th>Category</th>
                <td id=\"resource-type-category\"> </td>
            </tr>
            <tr>
                <th>Type</th>
                <td id=\"resource-type-type\"> </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "resource_type/show.html";
    }

    public function getDebugInfo()
    {
        return array (  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"modal\" id=\"infos-resource-type-modal\">
    <div class=\"modal-dialog\">
      <div class=\"modal-content\">
        <div class=\"modal-header\">
<div id=\"show-user\">

    <h1> Informations du type de ressource </h1>

    <table class=\"table\">
        <tbody>
            <tr>
                <th>Id</th>
                <td id=\"resource-type-id\"> </td>
            </tr>
            <tr>
                <th>Category</th>
                <td id=\"resource-type-category\"> </td>
            </tr>
            <tr>
                <th>Type</th>
                <td id=\"resource-type-type\"> </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
", "resource_type/show.html", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\resource_type\\show.html");
    }
}
