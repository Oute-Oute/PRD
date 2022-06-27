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

/* connexion/connexion.html.twig */
class __TwigTemplate_eaf1644376e7d92468a9ee95422e9dbcf77ef5c3883c6854618843b891d35a3a extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "connexion/connexion.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "connexion/connexion.html.twig"));

        // line 1
        echo "<!DOCTYPE html>


";
        // line 4
        $this->displayBlock('body', $context, $blocks);
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 5
        echo "\t<head>
\t\t<title>Connexion
\t\t</title>
\t\t<!--<script type=\"text/javascript\" src=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("js/connexion/connexion.js"), "html", null, true);
        echo "\" defer></script>-->
\t</head>

\t<h1>
\t\tConnexion
\t</h1>


\t<form
\t\tid=\"login-form\" method=\"post\">
\t\t<!-- Email input -->
\t\t<div>
\t\t\t<input type=\"text\" name=\"username\" id=\"username\" class=\"form-control\"/>
\t\t\t<label>Identifiant</label>
\t\t</div>

\t\t<!-- Password input -->
\t\t<div>
\t\t\t<input type=\"password\" name=\"password\" id=\"password\" class=\"form-control\"/>
\t\t\t<label>Mot de passe</label>
\t\t</div>

\t\t<!-- Submit button -->
\t\t<button type=\"submit\" id=\"submit_button\" class=\"btn btn-primary btn-block mb-4\">Connexion</button>
\t</form>

\t<p>
\t\t";
        // line 35
        echo twig_escape_filter($this->env, (isset($context["Message"]) || array_key_exists("Message", $context) ? $context["Message"] : (function () { throw new RuntimeError('Variable "Message" does not exist.', 35, $this->source); })()), "html", null, true);
        echo "
\t</p>


";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "connexion/connexion.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  103 => 35,  73 => 8,  68 => 5,  49 => 4,  44 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>


{% block body %}
\t<head>
\t\t<title>Connexion
\t\t</title>
\t\t<!--<script type=\"text/javascript\" src=\"{{ asset('js/connexion/connexion.js') }}\" defer></script>-->
\t</head>

\t<h1>
\t\tConnexion
\t</h1>


\t<form
\t\tid=\"login-form\" method=\"post\">
\t\t<!-- Email input -->
\t\t<div>
\t\t\t<input type=\"text\" name=\"username\" id=\"username\" class=\"form-control\"/>
\t\t\t<label>Identifiant</label>
\t\t</div>

\t\t<!-- Password input -->
\t\t<div>
\t\t\t<input type=\"password\" name=\"password\" id=\"password\" class=\"form-control\"/>
\t\t\t<label>Mot de passe</label>
\t\t</div>

\t\t<!-- Submit button -->
\t\t<button type=\"submit\" id=\"submit_button\" class=\"btn btn-primary btn-block mb-4\">Connexion</button>
\t</form>

\t<p>
\t\t{{Message}}
\t</p>


{% endblock %}
", "connexion/connexion.html.twig", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\connexion\\connexion.html.twig");
    }
}
