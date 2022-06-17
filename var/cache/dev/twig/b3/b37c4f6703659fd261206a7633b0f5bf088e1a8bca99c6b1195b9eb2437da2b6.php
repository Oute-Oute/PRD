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

/* global/menu.html */
class __TwigTemplate_c0afc5d386180ac846d970363b7bd4c48a9c804a8f6d11955c4209669be19f5f extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "global/menu.html"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "global/menu.html"));

        // line 1
        echo "
    <div class=\"dashboard-nav\">
        <header><a href=\"#!\" class=\"menu-toggle\"><i class=\"fas fa-bars\"></i></a><a href=\"#\"
                                                                                   class=\"brand-logo\"><i
                class=\"fas fa-anchor\"></i> <span>HOPITAL</span></a></header>
        <nav class=\"dashboard-nav-list\"><a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-home\"></i>
            Accueil </a><a
                href=\"#\" class=\"dashboard-nav-item active\"><i class=\"fas fa-tachometer-alt\"></i> Parcours</a>
        <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-file-upload\"></i> Planning </a>
        <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-file-upload\"></i> Éthique </a>
            <div class='dashboard-nav-dropdown'><a href=\"#!\" class=\"dashboard-nav-item dashboard-nav-dropdown-toggle\"><i
                    class=\"fas fa-photo-video\"></i> Ressources </a>
                <div class='dashboard-nav-dropdown-menu'><a href=\"#\"
                class=\"dashboard-nav-dropdown-item\">Humaines</a>
                <a href=\"#\" class=\"dashboard-nav-dropdown-item\">Matérielles</a></div>
            </div>
            <a href=\"consult_users\" class=\"dashboard-nav-item\"><i class=\"fas fa-user\"></i> Utilisateurs </a>
            <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-user\"></i> Mon profil </a>
          <div class=\"nav-item-divider\"></div>
          <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-sign-out-alt\"></i> Se déconnecter </a>
        </nav>
    </div>

";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "global/menu.html";
    }

    public function getDebugInfo()
    {
        return array (  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("
    <div class=\"dashboard-nav\">
        <header><a href=\"#!\" class=\"menu-toggle\"><i class=\"fas fa-bars\"></i></a><a href=\"#\"
                                                                                   class=\"brand-logo\"><i
                class=\"fas fa-anchor\"></i> <span>HOPITAL</span></a></header>
        <nav class=\"dashboard-nav-list\"><a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-home\"></i>
            Accueil </a><a
                href=\"#\" class=\"dashboard-nav-item active\"><i class=\"fas fa-tachometer-alt\"></i> Parcours</a>
        <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-file-upload\"></i> Planning </a>
        <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-file-upload\"></i> Éthique </a>
            <div class='dashboard-nav-dropdown'><a href=\"#!\" class=\"dashboard-nav-item dashboard-nav-dropdown-toggle\"><i
                    class=\"fas fa-photo-video\"></i> Ressources </a>
                <div class='dashboard-nav-dropdown-menu'><a href=\"#\"
                class=\"dashboard-nav-dropdown-item\">Humaines</a>
                <a href=\"#\" class=\"dashboard-nav-dropdown-item\">Matérielles</a></div>
            </div>
            <a href=\"consult_users\" class=\"dashboard-nav-item\"><i class=\"fas fa-user\"></i> Utilisateurs </a>
            <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-user\"></i> Mon profil </a>
          <div class=\"nav-item-divider\"></div>
          <a href=\"#\" class=\"dashboard-nav-item\"><i class=\"fas fa-sign-out-alt\"></i> Se déconnecter </a>
        </nav>
    </div>

", "global/menu.html", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\global\\menu.html");
    }
}
