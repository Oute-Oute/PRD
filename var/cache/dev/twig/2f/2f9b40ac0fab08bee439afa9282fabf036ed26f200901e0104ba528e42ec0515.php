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

/* planning/consultation-planning.html.twig */
class __TwigTemplate_9d61e76b3f2146832dfcd56edaae3907ed485092dcd8c3b8836b55781d516d96 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "planning/consultation-planning.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "planning/consultation-planning.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "planning/consultation-planning.html.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        echo "Consultation du Planning
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 5
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 6
        echo "\t<link rel=\"stylesheet\" href=\"";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("CSS/Calendrier/Calendrier.css"), "html", null, true);
        echo "\">

";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 9
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 10
        echo "\t<link href='js/fullcalendar-scheduler/lib/main.css' rel='stylesheet'/>
\t<script src='js/fullcalendar-scheduler/lib/main.js'></script>
\t<script src=\"js/planning/consultation-planning.js\"></script>
\t<script src=\"js/jquery-3.6.0.js\"></script>
\t<script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13\" crossorigin=\"anonymous\"></script>


\t<div class=\"all-container\">
\t\t<h1>Consultation du Planning</h1>
\t\t<div class=\"flex\">
\t\t\t<button class=\"btn-valide\" onclick=\"modify()\">Modifier le planning</button>
\t\t\t<select class=\"list-edit\" id=\"displayList\" onchange=\"changePlanning()\">
\t\t\t\t<option value=\"0\" selected disabled>Changer l'affichage
\t\t\t\t</option>
\t\t\t\t<option value=\"parcours\">Parcours</option>
\t\t\t\t<option value=\"patients\">Patients</option>
\t\t\t\t<option value=\"rh\">Ressources Humaines</option>
\t\t\t\t<option value=\"rm\">Ressources Matérielles</option>
\t\t\t</select>
\t\t\t<button id=\"filterbutton\" onclick=\"filterShow()\" class=\"btn-edit\" title=\"Filtrer l'affichage\">
\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" enable-background=\"new 0 0 24 24\" height=\"24px\" viewBox=\"0 0 24 24\" width=\"24px\"><g><path d=\"M0,0h24 M24,24H0\" fill=\"none\"/><path d=\"M4.25,5.61C6.27,8.2,10,13,10,13v6c0,0.55,0.45,1,1,1h2c0.55,0,1-0.45,1-1v-6c0,0,3.72-4.8,5.74-7.39 C20.25,4.95,19.78,4,18.95,4H5.04C4.21,4,3.74,4.95,4.25,5.61z\"/><path d=\"M0,0h24v24H0V0z\" fill=\"none\"/></g></svg></button>
\t\t\t<div id=\"filterId\" class=\"filter-container\" style=\"display:none\">
\t\t\t\t<div>
\t\t\t\t\t<input type=\"checkbox\" id=\"scales\" name=\"scales\" checked>
\t\t\t\t\t<label for=\"scales\">J'aime</label>
\t\t\t\t</div>

\t\t\t\t<div>
\t\t\t\t\t<input type=\"checkbox\" id=\"horns\" name=\"horns\">
\t\t\t\t\t<label for=\"horns\">Manger</label>
\t\t\t\t</div>
\t\t\t\t</div>
\t\t

\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-6 mx-auto\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<div class=\"datepicker date input-group\" style=\"z-index:0\">
\t\t\t\t\t\t<input type=\"date\" required placeholder=\"Choisir une date\" class=\"form-control\" id=\"Date\" onchange=\"changeDate()\" autocomplete=\"off\" title=\"Choisir une date à afficher\" value=\"";
        // line 48
        echo twig_escape_filter($this->env, (isset($context["datetoday"]) || array_key_exists("datetoday", $context) ? $context["datetoday"] : (function () { throw new RuntimeError('Variable "datetoday" does not exist.', 48, $this->source); })()), "html", null, true);
        echo "\".str_replace('T12:00:00','')>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
    \t<input type=hidden id=\"patients\" value='";
        // line 53
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listePatientsJSON"]) || array_key_exists("listePatientsJSON", $context) ? $context["listePatientsJSON"] : (function () { throw new RuntimeError('Variable "listePatientsJSON" does not exist.', 53, $this->source); })()), "content", [], "any", false, false, false, 53), "html", null, true);
        echo "'/>
\t\t<input type=hidden id=\"parcours\" value='";
        // line 54
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listeCircuitsJSON"]) || array_key_exists("listeCircuitsJSON", $context) ? $context["listeCircuitsJSON"] : (function () { throw new RuntimeError('Variable "listeCircuitsJSON" does not exist.', 54, $this->source); })()), "content", [], "any", false, false, false, 54), "html", null, true);
        echo "'/>
\t\t<div class=\"calendar-container\">
\t\t<div id='calendar'></div>
\t\t</div>
\t</div>
</div>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "planning/consultation-planning.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  166 => 54,  162 => 53,  154 => 48,  114 => 10,  104 => 9,  90 => 6,  80 => 5,  60 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Consultation du Planning
{% endblock %}
{% block stylesheets %}
\t<link rel=\"stylesheet\" href=\"{{ asset('CSS/Calendrier/Calendrier.css') }}\">

{% endblock %}
{% block body %}
\t<link href='js/fullcalendar-scheduler/lib/main.css' rel='stylesheet'/>
\t<script src='js/fullcalendar-scheduler/lib/main.js'></script>
\t<script src=\"js/planning/consultation-planning.js\"></script>
\t<script src=\"js/jquery-3.6.0.js\"></script>
\t<script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13\" crossorigin=\"anonymous\"></script>


\t<div class=\"all-container\">
\t\t<h1>Consultation du Planning</h1>
\t\t<div class=\"flex\">
\t\t\t<button class=\"btn-valide\" onclick=\"modify()\">Modifier le planning</button>
\t\t\t<select class=\"list-edit\" id=\"displayList\" onchange=\"changePlanning()\">
\t\t\t\t<option value=\"0\" selected disabled>Changer l'affichage
\t\t\t\t</option>
\t\t\t\t<option value=\"parcours\">Parcours</option>
\t\t\t\t<option value=\"patients\">Patients</option>
\t\t\t\t<option value=\"rh\">Ressources Humaines</option>
\t\t\t\t<option value=\"rm\">Ressources Matérielles</option>
\t\t\t</select>
\t\t\t<button id=\"filterbutton\" onclick=\"filterShow()\" class=\"btn-edit\" title=\"Filtrer l'affichage\">
\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" enable-background=\"new 0 0 24 24\" height=\"24px\" viewBox=\"0 0 24 24\" width=\"24px\"><g><path d=\"M0,0h24 M24,24H0\" fill=\"none\"/><path d=\"M4.25,5.61C6.27,8.2,10,13,10,13v6c0,0.55,0.45,1,1,1h2c0.55,0,1-0.45,1-1v-6c0,0,3.72-4.8,5.74-7.39 C20.25,4.95,19.78,4,18.95,4H5.04C4.21,4,3.74,4.95,4.25,5.61z\"/><path d=\"M0,0h24v24H0V0z\" fill=\"none\"/></g></svg></button>
\t\t\t<div id=\"filterId\" class=\"filter-container\" style=\"display:none\">
\t\t\t\t<div>
\t\t\t\t\t<input type=\"checkbox\" id=\"scales\" name=\"scales\" checked>
\t\t\t\t\t<label for=\"scales\">J'aime</label>
\t\t\t\t</div>

\t\t\t\t<div>
\t\t\t\t\t<input type=\"checkbox\" id=\"horns\" name=\"horns\">
\t\t\t\t\t<label for=\"horns\">Manger</label>
\t\t\t\t</div>
\t\t\t\t</div>
\t\t

\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-6 mx-auto\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<div class=\"datepicker date input-group\" style=\"z-index:0\">
\t\t\t\t\t\t<input type=\"date\" required placeholder=\"Choisir une date\" class=\"form-control\" id=\"Date\" onchange=\"changeDate()\" autocomplete=\"off\" title=\"Choisir une date à afficher\" value=\"{{datetoday}}\".str_replace('T12:00:00','')>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
    \t<input type=hidden id=\"patients\" value='{{listePatientsJSON.content}}'/>
\t\t<input type=hidden id=\"parcours\" value='{{listeCircuitsJSON.content}}'/>
\t\t<div class=\"calendar-container\">
\t\t<div id='calendar'></div>
\t\t</div>
\t</div>
</div>
{% endblock %}
", "planning/consultation-planning.html.twig", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\planning\\consultation-planning.html.twig");
    }
}
