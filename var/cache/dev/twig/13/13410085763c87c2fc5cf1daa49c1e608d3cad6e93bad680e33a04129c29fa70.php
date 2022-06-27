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

/* activity/modal_new.html.twig */
class __TwigTemplate_a55428d0c9cfb675dea2bed313d473b52355a7f36b347b54d099e2843337243e extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "activity/modal_new.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "activity/modal_new.html.twig"));

        // line 1
        echo "<div id=\"add-form\" >


        <div id = \"container\" >
            <h1 id=\"title\">Ajouter une nouvelle activité</h1>

            <form action=\"";
        // line 7
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_activity_new");
        echo "\" method=\"POST\">

                <div>
                    <label for='name'> Nom </label>
                    <input type='text' name='name'>
                </div>
                       <div>
                    <label for='duration'> Durée </label>
                    <input type='text' name='duration'>
                </div>
                <br>

            <button class=\"btn-consult btn-secondary\" onclick=\"window.location.href='";
        // line 19
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_activity_index");
        echo "'\">Consulter les activités</button>
            <button  type=\"submit\" class=\"btn-valide btn-secondary\"> Ajouter </button>
            </form>


        </div>

        <div id=\"background-shadow\" onclick='hideModalForm()'></div>
</div>";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "activity/modal_new.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 19,  51 => 7,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div id=\"add-form\" >


        <div id = \"container\" >
            <h1 id=\"title\">Ajouter une nouvelle activité</h1>

            <form action=\"{{ path('app_activity_new') }}\" method=\"POST\">

                <div>
                    <label for='name'> Nom </label>
                    <input type='text' name='name'>
                </div>
                       <div>
                    <label for='duration'> Durée </label>
                    <input type='text' name='duration'>
                </div>
                <br>

            <button class=\"btn-consult btn-secondary\" onclick=\"window.location.href='{{ path('app_activity_index') }}'\">Consulter les activités</button>
            <button  type=\"submit\" class=\"btn-valide btn-secondary\"> Ajouter </button>
            </form>


        </div>

        <div id=\"background-shadow\" onclick='hideModalForm()'></div>
</div>", "activity/modal_new.html.twig", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\activity\\modal_new.html.twig");
    }
}
