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

/* patient/index.html.twig */
class __TwigTemplate_e59050843628f315bd82d8a006e260033091f9fb132e9a4012306006d60ff81e extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patient/index.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "patient/index.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "patient/index.html.twig", 1);
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

        echo "Liste des patients
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
        echo "\t<!-- insertion de bootstrap et jquery -->
    <script src=\"js/Patient/patient.js\"></script>
    <script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-3nhVhzgkAiK+aRAouB5S914cEx9yGFCeToSirPZfaTPyy6g+RbDkzkmojJymfCBY sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13 sha256-SUzPu+ewjZCj6CtwVs9sNh6Q/LMFi1w1RZ9TxpKmVkE=\" crossorigin=\"anonymous\"></script>
  
    <!-- insertion des fichier js et css spécifique à modification planning -->
\t<link rel=\"stylesheet\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("CSS/Global/Modal.css"), "html", null, true);
        echo "\">
    <link rel=\"stylesheet\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("CSS/Global/IndexCrud.css"), "html", null, true);
        echo "\">
    <link rel=\"stylesheet\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("CSS/Global/NewCrud.css"), "html", null, true);
        echo "\">
    <script src=\"js/jquery-3.6.0.js\"></script>
\t
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 18
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 19
        echo "\t<div class=\"index\">
\t\t<h1 id=\"title\">Liste des patients</h1>

\t\t<!-- bouton pour l'ajout d'un nouveau patient -->
\t\t<button type=\"button\" class=\"btn-add\" onclick=\"addPatient()\">Ajouter un nouveau patient</button>

\t\t<!-- affichage de la liste de tous les patients -->
\t\t<table class=\"table table-striped\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Identifiant</th>
\t\t\t\t\t<th>Nom</th>
\t\t\t\t\t<th>Prénom</th> 
\t\t\t\t\t<th>Actions</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t\t";
        // line 36
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["patients"]) || array_key_exists("patients", $context) ? $context["patients"] : (function () { throw new RuntimeError('Variable "patients" does not exist.', 36, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["patient"]) {
            // line 37
            echo "\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>";
            // line 38
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 38), "html", null, true);
            echo "</td>
\t\t\t\t\t\t<td>";
            // line 39
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "lastname", [], "any", false, false, false, 39), "html", null, true);
            echo "</td>
\t\t\t\t\t\t<td>";
            // line 40
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "firstname", [], "any", false, false, false, 40), "html", null, true);
            echo "</td>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t    <button class = \"btn-infos btn-secondary\" onclick=\"showInfosPatient('";
            // line 42
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 42), "html", null, true);
            echo "', '";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "lastname", [], "any", false, false, false, 42), "html", null, true);
            echo "' , '";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "firstname", [], "any", false, false, false, 42), "html", null, true);
            echo "')\">Informations</a>
\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-edit btn-secondary\" onclick=\"editPatient('";
            // line 43
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 43), "html", null, true);
            echo "', '";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "lastname", [], "any", false, false, false, 43), "html", null, true);
            echo "', '";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "firstname", [], "any", false, false, false, 43), "html", null, true);
            echo "')\">Éditer</a>
\t\t\t\t\t\t\t<form method=\"post\" action=\"";
            // line 44
            echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("PatientDelete", ["id" => twig_get_attribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 44)]), "html", null, true);
            echo "\" onsubmit=\"return confirm('Voulez vous vraiment supprimer ce patient ?');\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"patient\" value=\"";
            // line 45
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["patient"], "id", [], "any", false, false, false, 45), "html", null, true);
            echo "\">
\t\t\t\t\t\t\t\t<button class=\"btn-delete btn-secondary\">Supprimer</button>
\t\t\t\t\t\t\t</form>
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 51
            echo "\t\t\t\t\t<tr>
\t\t\t\t\t\t<td colspan=\"5\">Pas de patients créés !</td>
\t\t\t\t\t</tr>
\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['patient'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        echo "\t\t\t</tbody>
\t\t</table>

    ";
        // line 58
        echo twig_include($this->env, $context, "patient/edit_patient.html.twig");
        echo "
\t";
        // line 59
        echo twig_include($this->env, $context, "patient/new_patient.html.twig");
        echo "
\t";
        // line 60
        echo twig_include($this->env, $context, "patient/show_patient.html.twig");
        echo "





\t";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "patient/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  222 => 60,  218 => 59,  214 => 58,  209 => 55,  200 => 51,  189 => 45,  185 => 44,  177 => 43,  169 => 42,  164 => 40,  160 => 39,  156 => 38,  153 => 37,  148 => 36,  129 => 19,  119 => 18,  105 => 13,  101 => 12,  97 => 11,  90 => 6,  80 => 5,  60 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Liste des patients
{% endblock %}
{% block stylesheets %}
\t<!-- insertion de bootstrap et jquery -->
    <script src=\"js/Patient/patient.js\"></script>
    <script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-3nhVhzgkAiK+aRAouB5S914cEx9yGFCeToSirPZfaTPyy6g+RbDkzkmojJymfCBY sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13 sha256-SUzPu+ewjZCj6CtwVs9sNh6Q/LMFi1w1RZ9TxpKmVkE=\" crossorigin=\"anonymous\"></script>
  
    <!-- insertion des fichier js et css spécifique à modification planning -->
\t<link rel=\"stylesheet\" href=\"{{ asset('CSS/Global/Modal.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('CSS/Global/IndexCrud.css') }}\">
    <link rel=\"stylesheet\" href=\"{{ asset('CSS/Global/NewCrud.css') }}\">
    <script src=\"js/jquery-3.6.0.js\"></script>
\t
{% endblock %}

{% block body %}
\t<div class=\"index\">
\t\t<h1 id=\"title\">Liste des patients</h1>

\t\t<!-- bouton pour l'ajout d'un nouveau patient -->
\t\t<button type=\"button\" class=\"btn-add\" onclick=\"addPatient()\">Ajouter un nouveau patient</button>

\t\t<!-- affichage de la liste de tous les patients -->
\t\t<table class=\"table table-striped\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Identifiant</th>
\t\t\t\t\t<th>Nom</th>
\t\t\t\t\t<th>Prénom</th> 
\t\t\t\t\t<th>Actions</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t\t{% for patient in patients %}
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>{{ patient.id }}</td>
\t\t\t\t\t\t<td>{{ patient.lastname }}</td>
\t\t\t\t\t\t<td>{{ patient.firstname }}</td>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t    <button class = \"btn-infos btn-secondary\" onclick=\"showInfosPatient('{{patient.id}}', '{{patient.lastname}}' , '{{ patient.firstname }}')\">Informations</a>
\t\t\t\t\t\t\t<button type=\"button\" class=\"btn-edit btn-secondary\" onclick=\"editPatient('{{ patient.id }}', '{{ patient.lastname }}', '{{ patient.firstname }}')\">Éditer</a>
\t\t\t\t\t\t\t<form method=\"post\" action=\"{{ path('PatientDelete', {'id': patient.id}) }}\" onsubmit=\"return confirm('Voulez vous vraiment supprimer ce patient ?');\">
\t\t\t\t\t\t\t\t<input type=\"hidden\" name=\"patient\" value=\"{{ patient.id }}\">
\t\t\t\t\t\t\t\t<button class=\"btn-delete btn-secondary\">Supprimer</button>
\t\t\t\t\t\t\t</form>
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t{% else %}
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td colspan=\"5\">Pas de patients créés !</td>
\t\t\t\t\t</tr>
\t\t\t\t{% endfor %}
\t\t\t</tbody>
\t\t</table>

    {{ include ('patient/edit_patient.html.twig') }}
\t{{ include ('patient/new_patient.html.twig') }}
\t{{ include ('patient/show_patient.html.twig') }}





\t{% endblock %}
", "patient/index.html.twig", "C:\\Users\\mdpVirgile\\Documents\\GitHub\\Projet_Stage_DI4\\templates\\patient\\index.html.twig");
    }
}
