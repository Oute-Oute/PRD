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

/* material_resource/show.html */
class __TwigTemplate_0ac33a09935ff9a93a76860dfabddc8ebfb960011dca4c9ca321b1783b4e3bce extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "material_resource/show.html"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "material_resource/show.html"));

        // line 1
        echo "<div class=\"modal\" id=\"infos-material-resource-modal\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
    <h1>MaterialResource</h1>

    <table class=\"table table-striped\">
        <tbody>
            <tr>
                <th>Id</th>
                <td id =\"material-resource-id\"></td>
            </tr>
            <tr>
                <th>Materialresourcename</th>
                <td id = \"material-resource-name\"></td>
            </tr>
            <tr>
                <th>Available</th>
                <td id = \"material-resource-available\"></td>
            </tr>
        </tbody>
    </table>

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
        return "material_resource/show.html";
    }

    public function getDebugInfo()
    {
        return array (  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"modal\" id=\"infos-material-resource-modal\">
    <div class=\"modal-dialog\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
    <h1>MaterialResource</h1>

    <table class=\"table table-striped\">
        <tbody>
            <tr>
                <th>Id</th>
                <td id =\"material-resource-id\"></td>
            </tr>
            <tr>
                <th>Materialresourcename</th>
                <td id = \"material-resource-name\"></td>
            </tr>
            <tr>
                <th>Available</th>
                <td id = \"material-resource-available\"></td>
            </tr>
        </tbody>
    </table>

</div>
</div>
</div>
</div>
", "material_resource/show.html", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\material_resource\\show.html");
    }
}
