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

/* human_resource/new.html.twig */
class __TwigTemplate_f0fd116f3c5557533e1820acd3e8f6e2a8b58ab4b15fc1ebb10a05d8da11c25f extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "human_resource/new.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "human_resource/new.html.twig"));

        // line 1
        echo "<div class=\"modal\" id=\"new-activity-modal\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h2 class=\"modal-title\">Ajouter l'activité <span id=\"show-title\"></span></h2>
      </div>

  <form action=\"";
        // line 8
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_human_resource_new");
        echo "\" method=\"POST\">
            <div class=\"modal-body\">
                <div>
                    <label for='name'> Nom </label>
                    <input type='text' name='name'>
                </div>
                <div>
                    <label for='dispo'> Disponibilité </label>
                    <input type='text' name='dispo'>
                </div>
            </div>
            <br>

            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn-consult btn-secondary\" onclick=\"window.location.href='";
        // line 22
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_activity_index");
        echo "'\">Consulter les activités</button>
                <button  type=\"submit\" class=\"btn-valide btn-secondary\"> Ajouter </button>
            </div>

   
        </form>

       
    </div>
  </div>
</div>
";
        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "human_resource/new.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 22,  52 => 8,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"modal\" id=\"new-activity-modal\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h2 class=\"modal-title\">Ajouter l'activité <span id=\"show-title\"></span></h2>
      </div>

  <form action=\"{{ path('app_human_resource_new') }}\" method=\"POST\">
            <div class=\"modal-body\">
                <div>
                    <label for='name'> Nom </label>
                    <input type='text' name='name'>
                </div>
                <div>
                    <label for='dispo'> Disponibilité </label>
                    <input type='text' name='dispo'>
                </div>
            </div>
            <br>

            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn-consult btn-secondary\" onclick=\"window.location.href='{{ path('app_activity_index') }}'\">Consulter les activités</button>
                <button  type=\"submit\" class=\"btn-valide btn-secondary\"> Ajouter </button>
            </div>

   
        </form>

       
    </div>
  </div>
</div>
", "human_resource/new.html.twig", "C:\\Users\\Clement\\Desktop\\Projet Stage DI4\\Projet_Stage_DI4\\templates\\human_resource\\new.html.twig");
    }
}
