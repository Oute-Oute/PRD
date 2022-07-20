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

/* planning/modification-planning.html.twig */
class __TwigTemplate_8d05cfffa3914b0abd2e978eadb61b189583d53df9bb870374a4a4d7a4de2af2 extends Template
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
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "planning/modification-planning.html.twig"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "planning/modification-planning.html.twig"));

        $this->parent = $this->loadTemplate("base.html.twig", "planning/modification-planning.html.twig", 1);
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

        echo "Modification du Planning
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 6
    public function block_stylesheets($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "stylesheets"));

        // line 7
        echo "
\t<!-- insertion de bootstrap, jquery et fullcalendar-scheduler -->
\t<link href='js/fullcalendar-scheduler/lib/main.css' rel='stylesheet'/>
\t<script src='js/fullcalendar-scheduler/lib/main.js'></script>
    <script src=\"js/jquery-ui/external/jquery/jquery.js\"></script>
 \t<script src=\"js/popper.min.js\"></script>  
\t<script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-3nhVhzgkAiK+aRAouB5S914cEx9yGFCeToSirPZfaTPyy6g+RbDkzkmojJymfCBY sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13 sha256-SUzPu+ewjZCj6CtwVs9sNh6Q/LMFi1w1RZ9TxpKmVkE=\" crossorigin=\"anonymous\"></script>

\t<!-- insertion des fichier js et css spécifique à modification planning -->
\t<script src=\"js/planning/modification-planning.js\"></script>
\t<link rel=\"stylesheet\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\AssetExtension']->getAssetUrl("CSS/Calendrier/Calendrier.css"), "html", null, true);
        echo "\">

";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    // line 21
    public function block_body($context, array $blocks = [])
    {
        $macros = $this->macros;
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->enter($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 22
        echo "
\t";
        // line 23
        echo twig_include($this->env, $context, "planning/modal_popup.html.twig");
        echo "

\t<!-- modify-planning-modal -->

\t<div class=\"modal\" id=\"modify-planning-modal\">
\t\t<div class=\"modal-dialog\">
\t\t\t<div class=\"modal-content\">
\t\t\t\t<div class=\"modal-header\">
\t\t\t\t\t<h2 class=\"modal-title\">Modifier
\t\t\t\t\t\t<span id=\"show-modified-event-title\"></span>
\t\t\t\t\t</h2>
\t\t\t\t</div>
\t\t\t\t<div class=\"modal-body\">
\t\t\t\t\t<label class=\"label-event\">Début de l'activité
\t\t\t\t\t</label>
\t\t\t\t\t<div class=\"input-container onWhite\">
\t\t\t\t\t\t<!-- création d'input caché permettant de transmettre des informations entre le php, le twig et le js -->
\t\t\t\t\t\t<input id=\"id-modified-event\" name=\"id-modified-event\" type=\"hidden\">

\t\t\t\t\t\t<input class=\"input-modal-date\" type=\"time\" id=\"start-modified-event\" name=\"start-modified-event\" required>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Parcours :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"parcours-modified-event\" name=\"parcours-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Patient :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"patient-modified-event\" name=\"patient-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Ressource Humaine :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"human-resource-modified-event\" name=\"human-resource-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Ressource Matérielle :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"material-resource-modified-event\" name=\"material-resource-modified-event\" disabled></input>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t<!-- bouton de suppression à revoir, permet pour le moment d'être redirigé vers la gestion des rendez-vous pour en supprimer un -->
\t\t\t\t\t\t<form method=\"get\" action=\"";
        // line 62
        echo $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("Appointment");
        echo "\">
\t\t\t\t\t\t\t<button class=\"btn-delete btn-secondary\" onclick=\"return confirm('Voulez-vous être redirigé vers la gestion des rendez-vous ? Vos modifications vont être perdues');\">Supprimer le rendez-vous</button>
\t\t\t\t\t\t</form>

\t\t\t\t\t\t<!-- bouton permettant de fermer la modal ou de valider les modification -->
\t\t\t\t\t\t<button type=\"button\" class=\"btn-consult btn-secondary\" data-bs-dismiss=\"modal\">Annuler</button>
\t\t\t\t\t\t<button type=\"submit\" class=\"btn-valide btn-secondary\" onclick=\"modifyEvent()\">Valider</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>


\t<!-- add-planning-modal -->

\t<div class=\"modal\" id=\"add-planning-modal\">
\t\t<!-- div qui représente la modal -->
\t\t<div
\t\t\tclass=\"modal-dialog\">
\t\t\t<!-- permet d'afficher la modal en mode dialog -->
\t\t\t<div
\t\t\t\tclass=\"modal-content\">
\t\t\t\t<!-- permet de structurer le contenu de la modal -->
\t\t\t\t<div
\t\t\t\t\tclass=\"modal-header\">
\t\t\t\t\t<!-- sépare le header de la modal -->
\t\t\t\t\t<h2 class=\"modal-title\">Ajouter un rendez-vous</h2>
\t\t\t\t</div>
\t\t\t\t<div
\t\t\t\t\tclass=\"modal-body\">
\t\t\t\t\t<!-- sépare le body de la modal -->
\t\t\t\t\t<input id=\"date\" name=\"date\" type=\"hidden\" value=";
        // line 94
        echo twig_escape_filter($this->env, (isset($context["currentdate"]) || array_key_exists("currentdate", $context) ? $context["currentdate"] : (function () { throw new RuntimeError('Variable "currentdate" does not exist.', 94, $this->source); })()), "html", null, true);
        echo ">
\t\t\t\t\t<input id=\"listeAppointments\" name=\"listeAppointments\" type='hidden' value=";
        // line 95
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listAppointmentsJSON"]) || array_key_exists("listAppointmentsJSON", $context) ? $context["listAppointmentsJSON"] : (function () { throw new RuntimeError('Variable "listAppointmentsJSON" does not exist.', 95, $this->source); })()), "content", [], "any", false, false, false, 95), "html", null, true);
        echo ">
\t\t\t\t\t<input id=\"listeSuccessors\" name=\"listeSuccessors\" type='hidden' value=";
        // line 96
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listSuccessorsJSON"]) || array_key_exists("listSuccessorsJSON", $context) ? $context["listSuccessorsJSON"] : (function () { throw new RuntimeError('Variable "listSuccessorsJSON" does not exist.', 96, $this->source); })()), "content", [], "any", false, false, false, 96), "html", null, true);
        echo ">
\t\t\t\t\t<input id=\"listeActivities\" name=\"listeActivities\" type='hidden' value=";
        // line 97
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listActivitiesJSON"]) || array_key_exists("listActivitiesJSON", $context) ? $context["listActivitiesJSON"] : (function () { throw new RuntimeError('Variable "listActivitiesJSON" does not exist.', 97, $this->source); })()), "content", [], "any", false, false, false, 97), "html", null, true);
        echo ">

\t\t\t\t\t<div
\t\t\t\t\t\tid=\"select-container-patient\">
\t\t\t\t\t\t<!-- Selection du rendez-vous -->
\t\t\t\t\t\t<label>Choisissez le rendez-vous</label>
\t\t\t\t\t\t<select id=\"select-appointment\" name=\"select-appointment\" required>
\t\t\t\t\t\t</select>
\t\t\t\t\t</div>

\t\t\t\t\t<div
\t\t\t\t\t\tid=\"select-container-date\" class=\"input-container onWhite\">
\t\t\t\t\t\t<!-- Selection de l'heure de début du rdv -->
\t\t\t\t\t\t<label>Heure de début du parcours :
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input class=\"input-field\" type=\"time\" id=\"timeBegin\" name=\"timeBegin\" required>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t<button type=\"button\" class=\"btn-consult btn-secondary\" data-bs-dismiss=\"modal\">Annuler</button>
\t\t\t\t\t\t<button type=\"submit\" class=\"btn-valide btn-secondary\" onclick=\"AddEventValider()\">Valider</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</form>
\t\t</div>
\t</div>
</div>

<input type=\"hidden\" id=\"material\" value='";
        // line 125
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listMaterialResourceJSON"]) || array_key_exists("listMaterialResourceJSON", $context) ? $context["listMaterialResourceJSON"] : (function () { throw new RuntimeError('Variable "listMaterialResourceJSON" does not exist.', 125, $this->source); })()), "content", [], "any", false, false, false, 125), "html", null, true);
        echo "'/>
<input type=\"hidden\" id=\"human\" value='";
        // line 126
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listHumanResourceJSON"]) || array_key_exists("listHumanResourceJSON", $context) ? $context["listHumanResourceJSON"] : (function () { throw new RuntimeError('Variable "listHumanResourceJSON" does not exist.', 126, $this->source); })()), "content", [], "any", false, false, false, 126), "html", null, true);
        echo "'/>
<input type='hidden' id=\"listeAppointments\" name=\"listeAppointments\" value='";
        // line 127
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listAppointmentsJSON"]) || array_key_exists("listAppointmentsJSON", $context) ? $context["listAppointmentsJSON"] : (function () { throw new RuntimeError('Variable "listAppointmentsJSON" does not exist.', 127, $this->source); })()), "content", [], "any", false, false, false, 127), "html", null, true);
        echo "'>
<input type=\"hidden\" id=\"listScheduledActivitiesJSON\" value='";
        // line 128
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listScheduledActivitiesJSON"]) || array_key_exists("listScheduledActivitiesJSON", $context) ? $context["listScheduledActivitiesJSON"] : (function () { throw new RuntimeError('Variable "listScheduledActivitiesJSON" does not exist.', 128, $this->source); })()), "content", [], "any", false, false, false, 128), "html", null, true);
        echo "'/>
<input id=\"date\" name=\"date\" type=\"hidden\" value=";
        // line 129
        echo twig_escape_filter($this->env, (isset($context["currentdate"]) || array_key_exists("currentdate", $context) ? $context["currentdate"] : (function () { throw new RuntimeError('Variable "currentdate" does not exist.', 129, $this->source); })()), "html", null, true);
        echo ">
<input id=\"listeSuccessors\" name=\"listeSuccessors\" type='hidden' value=";
        // line 130
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listSuccessorsJSON"]) || array_key_exists("listSuccessorsJSON", $context) ? $context["listSuccessorsJSON"] : (function () { throw new RuntimeError('Variable "listSuccessorsJSON" does not exist.', 130, $this->source); })()), "content", [], "any", false, false, false, 130), "html", null, true);
        echo ">
<input id=\"listeActivities\" name=\"listeActivities\" type='hidden' value=";
        // line 131
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listActivitiesJSON"]) || array_key_exists("listActivitiesJSON", $context) ? $context["listActivitiesJSON"] : (function () { throw new RuntimeError('Variable "listActivitiesJSON" does not exist.', 131, $this->source); })()), "content", [], "any", false, false, false, 131), "html", null, true);
        echo ">
<input id=\"listeActivityHumanResource\" name=\"listeActivityHumanResource\" type=\"hidden\" value=";
        // line 132
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listActivityHumanResourcesJSON"]) || array_key_exists("listActivityHumanResourcesJSON", $context) ? $context["listActivityHumanResourcesJSON"] : (function () { throw new RuntimeError('Variable "listActivityHumanResourcesJSON" does not exist.', 132, $this->source); })()), "content", [], "any", false, false, false, 132), "html", null, true);
        echo ">
<input id=\"listeActivityMaterialResource\" name=\"listeActivityMaterialResource\" type='hidden' value=";
        // line 133
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["listActivityMaterialResourcesJSON"]) || array_key_exists("listActivityMaterialResourcesJSON", $context) ? $context["listActivityMaterialResourcesJSON"] : (function () { throw new RuntimeError('Variable "listActivityMaterialResourcesJSON" does not exist.', 133, $this->source); })()), "content", [], "any", false, false, false, 133), "html", null, true);
        echo ">
<input id=\"categoryOfMaterialResourceJSON\" name=\"categoryOfMaterialResourceJSON\" type='hidden' value=";
        // line 134
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["categoryOfMaterialResourceJSON"]) || array_key_exists("categoryOfMaterialResourceJSON", $context) ? $context["categoryOfMaterialResourceJSON"] : (function () { throw new RuntimeError('Variable "categoryOfMaterialResourceJSON" does not exist.', 134, $this->source); })()), "content", [], "any", false, false, false, 134), "html", null, true);
        echo ">
<input id=\"categoryOfHumanResourceJSON\" name=\"categoryOfHumanResourceJSON\" type='hidden' value=";
        // line 135
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["categoryOfHumanResourceJSON"]) || array_key_exists("categoryOfHumanResourceJSON", $context) ? $context["categoryOfHumanResourceJSON"] : (function () { throw new RuntimeError('Variable "categoryOfHumanResourceJSON" does not exist.', 135, $this->source); })()), "content", [], "any", false, false, false, 135), "html", null, true);
        echo ">

";
        // line 137
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["settingsRepository"]) || array_key_exists("settingsRepository", $context) ? $context["settingsRepository"] : (function () { throw new RuntimeError('Variable "settingsRepository" does not exist.', 137, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["setting"]) {
            // line 138
            echo "\t<input type=\"hidden\" name=\"modifAlertTime\" id=\"modifAlertTime\" value='";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["setting"], "getAlertmodificationtimer", [], "any", false, false, false, 138), "html", null, true);
            echo "'>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['setting'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 140
        echo "
<div class=\"all-container\" style=\"display: flex; flex-direction: row;\">
<div style=\"width:100%;\">

\t<h1>Modification du Planning du ";
        // line 144
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["currentdate"]) || array_key_exists("currentdate", $context) ? $context["currentdate"] : (function () { throw new RuntimeError('Variable "currentdate" does not exist.', 144, $this->source); })()), "d/m/Y"), "html", null, true);
        echo "</h1>
\t<div id=\"container-top\">
\t\t<div
\t\t\tclass=\"CRUD-div\">
\t\t\t<!-- bouton d'ajout d'un rendez-vous dans le planning -->
\t\t\t<button type=\"button\" class=\"btn-calendar-edit\" onclick=\"addEvent()\">Ajouter</button>

\t\t\t<!-- filtre de l'affichage par catégorie -->
\t\t\t<select class=\"btn-calendar-edit\" id=\"displayList\" onchange=\"changePlanning()\">
\t\t\t\t<option value=\"0\" selected disabled>Changer l'affichage
\t\t\t\t</option>
\t\t\t\t<option value=\"rh\">Ressources Humaines</option>
\t\t\t\t<option value=\"rm\">Ressources Matérielles</option>
\t\t\t</select>

\t\t\t<!-- bouton pour filtrer l'affichage (pas encore opérationnel) -->
\t\t\t<button id=\"filterbutton\" onclick=\"filterShow()\" class=\"btn-calendar-edit\" title=\"Filtrer l'affichage\">
\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" enable-background=\"new 0 0 24 24\" height=\"24px\" viewbox=\"0 0 24 24\" width=\"24px\">
\t\t\t\t\t<g><path d=\"M0,0h24 M24,24H0\" fill=\"none\"/><path d=\"M4.25,5.61C6.27,8.2,10,13,10,13v6c0,0.55,0.45,1,1,1h2c0.55,0,1-0.45,1-1v-6c0,0,3.72-4.8,5.74-7.39 C20.25,4.95,19.78,4,18.95,4H5.04C4.21,4,3.74,4.95,4.25,5.61z\"/><path d=\"M0,0h24v24H0V0z\" fill=\"none\"/></g>
\t\t\t\t</svg>
\t\t\t</button>
\t\t\t<div id=\"filterId\" class=\"filter-container\" style=\"display:none\"></div>
\t\t</div>
\t\t
\t\t<!-- liste des boutons pour annuler ou valider les modifications -->


\t\t<div id='container-top-right'>
\t\t\t<button type=\"button\" class=\"btn-calendar-edit\" onclick=\"undoEvent()\">Retour en Arrière</button>
\t\t\t<button type=\"button\" class=\"btn-delete btn-secondary\" onclick=\"window.location.assign('/ModificationDeleteOnUnload?dateModified=' + \$_GET('date') + '&id=' + \$_GET('id'))\">Annuler</button>
\t\t\t";
        // line 174
        if (twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 174, $this->source); })()), "user", [], "any", false, false, false, 174)) {
            // line 175
            echo "\t\t\t\t";
            $context["userIdentifier"] = twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 175, $this->source); })()), "user", [], "any", false, false, false, 175), "getUserIdentifier", [], "method", false, false, false, 175);
            // line 176
            echo "\t\t\t";
        } else {
            // line 177
            echo "\t\t\t\t";
            $context["userIdentifier"] = "";
            // line 178
            echo "\t\t\t";
        }
        // line 179
        echo "\t\t\t<form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("ModificationPlanningValidation", ["username" => (isset($context["userIdentifier"]) || array_key_exists("userIdentifier", $context) ? $context["userIdentifier"] : (function () { throw new RuntimeError('Variable "userIdentifier" does not exist.', 179, $this->source); })())]), "html", null, true);
        echo "\">
\t\t\t\t<button type=\"submit\" class=\"btn-edit btn-secondary\" onclick=\"setEvents()\">Valider</button>
\t\t\t\t<input type=\"hidden\" id='events' name=\"events\"/>
\t\t\t\t<input type=\"hidden\" id='validation-date' name=\"validation-date\"/>
\t\t\t\t<input type=\"hidden\" id='list-resource' name=\"list-resource\"/>
\t\t\t</form>
\t\t</div>
\t</div>
\t  
\t<div
\t\tid=\"calendar-container\" class=\"calendar-container\">

\t\t<select name=\"zoom\" onclick=\"zoomChange()\" class=\"form-select\" id=\"zoom\" >
\t\t\t<option value=\"02:00:00\">Zoom : 2H</option>
\t\t\t<option value=\"01:00:00\">Zoom : 1H</option>
\t\t\t<option value=\"0:40:00\">Zoom : 40min</option>
\t\t\t<option value='00:20:00' selected>Zoom : 20min (Default)</option>
\t\t\t<option value=\"00:10:00\">Zoom : 10min</option>
\t\t\t<option value=\"00:05:00\">Zoom : 5min</option>
\t\t\t<option value=\"00:03:00\">Zoom : 1min</option>
\t\t</select>

\t\t<!-- affichage du calendar -->
\t\t<div id='calendar'></div>
\t</div>
\t</div>

\t<div id=\"lateral-panel\">
\t\t<input id=\"lateral-panel-input\" type=\"checkbox\" onclick=\"displayListErrorMessages()\">
\t\t<label id=\"lateral-panel-label\" for=\"lateral-panel-input\"></label>
\t\t<div id=\"lateral-panel-bloc\">
\t\t\t<div style='text-align:center'>
\t\t\t\t<h2>Liste des erreurs de Plannification</h2>
\t\t\t</div>
\t\t</div>
\t</div>
</div>


<script>alertOnload();</script>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

        
        $__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e->leave($__internal_085b0142806202599c7fe3b329164a92397d8978207a37e79d70b8c52599e33e_prof);

    }

    public function getTemplateName()
    {
        return "planning/modification-planning.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  359 => 179,  356 => 178,  353 => 177,  350 => 176,  347 => 175,  345 => 174,  312 => 144,  306 => 140,  297 => 138,  293 => 137,  288 => 135,  284 => 134,  280 => 133,  276 => 132,  272 => 131,  268 => 130,  264 => 129,  260 => 128,  256 => 127,  252 => 126,  248 => 125,  217 => 97,  213 => 96,  209 => 95,  205 => 94,  170 => 62,  128 => 23,  125 => 22,  115 => 21,  102 => 17,  90 => 7,  80 => 6,  60 => 3,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block title %}Modification du Planning
{% endblock %}

{% block stylesheets %}

\t<!-- insertion de bootstrap, jquery et fullcalendar-scheduler -->
\t<link href='js/fullcalendar-scheduler/lib/main.css' rel='stylesheet'/>
\t<script src='js/fullcalendar-scheduler/lib/main.js'></script>
    <script src=\"js/jquery-ui/external/jquery/jquery.js\"></script>
 \t<script src=\"js/popper.min.js\"></script>  
\t<script src=\"js/bootstrap/bootstrap.min.js\" integrity=\"sha384-3nhVhzgkAiK+aRAouB5S914cEx9yGFCeToSirPZfaTPyy6g+RbDkzkmojJymfCBY sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13 sha256-SUzPu+ewjZCj6CtwVs9sNh6Q/LMFi1w1RZ9TxpKmVkE=\" crossorigin=\"anonymous\"></script>

\t<!-- insertion des fichier js et css spécifique à modification planning -->
\t<script src=\"js/planning/modification-planning.js\"></script>
\t<link rel=\"stylesheet\" href=\"{{ asset('CSS/Calendrier/Calendrier.css') }}\">

{% endblock %}

{% block body %}

\t{{include('planning/modal_popup.html.twig')}}

\t<!-- modify-planning-modal -->

\t<div class=\"modal\" id=\"modify-planning-modal\">
\t\t<div class=\"modal-dialog\">
\t\t\t<div class=\"modal-content\">
\t\t\t\t<div class=\"modal-header\">
\t\t\t\t\t<h2 class=\"modal-title\">Modifier
\t\t\t\t\t\t<span id=\"show-modified-event-title\"></span>
\t\t\t\t\t</h2>
\t\t\t\t</div>
\t\t\t\t<div class=\"modal-body\">
\t\t\t\t\t<label class=\"label-event\">Début de l'activité
\t\t\t\t\t</label>
\t\t\t\t\t<div class=\"input-container onWhite\">
\t\t\t\t\t\t<!-- création d'input caché permettant de transmettre des informations entre le php, le twig et le js -->
\t\t\t\t\t\t<input id=\"id-modified-event\" name=\"id-modified-event\" type=\"hidden\">

\t\t\t\t\t\t<input class=\"input-modal-date\" type=\"time\" id=\"start-modified-event\" name=\"start-modified-event\" required>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Parcours :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"parcours-modified-event\" name=\"parcours-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Patient :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"patient-modified-event\" name=\"patient-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Ressource Humaine :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"human-resource-modified-event\" name=\"human-resource-modified-event\" disabled></input>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<label class=\"label-event\">Ressource Matérielle :</label>
\t\t\t\t\t\t</br>
\t\t\t\t\t\t<input class=\"input-modal\" type=\"text\" id=\"material-resource-modified-event\" name=\"material-resource-modified-event\" disabled></input>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t<!-- bouton de suppression à revoir, permet pour le moment d'être redirigé vers la gestion des rendez-vous pour en supprimer un -->
\t\t\t\t\t\t<form method=\"get\" action=\"{{ path('Appointment') }}\">
\t\t\t\t\t\t\t<button class=\"btn-delete btn-secondary\" onclick=\"return confirm('Voulez-vous être redirigé vers la gestion des rendez-vous ? Vos modifications vont être perdues');\">Supprimer le rendez-vous</button>
\t\t\t\t\t\t</form>

\t\t\t\t\t\t<!-- bouton permettant de fermer la modal ou de valider les modification -->
\t\t\t\t\t\t<button type=\"button\" class=\"btn-consult btn-secondary\" data-bs-dismiss=\"modal\">Annuler</button>
\t\t\t\t\t\t<button type=\"submit\" class=\"btn-valide btn-secondary\" onclick=\"modifyEvent()\">Valider</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>


\t<!-- add-planning-modal -->

\t<div class=\"modal\" id=\"add-planning-modal\">
\t\t<!-- div qui représente la modal -->
\t\t<div
\t\t\tclass=\"modal-dialog\">
\t\t\t<!-- permet d'afficher la modal en mode dialog -->
\t\t\t<div
\t\t\t\tclass=\"modal-content\">
\t\t\t\t<!-- permet de structurer le contenu de la modal -->
\t\t\t\t<div
\t\t\t\t\tclass=\"modal-header\">
\t\t\t\t\t<!-- sépare le header de la modal -->
\t\t\t\t\t<h2 class=\"modal-title\">Ajouter un rendez-vous</h2>
\t\t\t\t</div>
\t\t\t\t<div
\t\t\t\t\tclass=\"modal-body\">
\t\t\t\t\t<!-- sépare le body de la modal -->
\t\t\t\t\t<input id=\"date\" name=\"date\" type=\"hidden\" value={{currentdate}}>
\t\t\t\t\t<input id=\"listeAppointments\" name=\"listeAppointments\" type='hidden' value={{listAppointmentsJSON.content}}>
\t\t\t\t\t<input id=\"listeSuccessors\" name=\"listeSuccessors\" type='hidden' value={{listSuccessorsJSON.content}}>
\t\t\t\t\t<input id=\"listeActivities\" name=\"listeActivities\" type='hidden' value={{listActivitiesJSON.content}}>

\t\t\t\t\t<div
\t\t\t\t\t\tid=\"select-container-patient\">
\t\t\t\t\t\t<!-- Selection du rendez-vous -->
\t\t\t\t\t\t<label>Choisissez le rendez-vous</label>
\t\t\t\t\t\t<select id=\"select-appointment\" name=\"select-appointment\" required>
\t\t\t\t\t\t</select>
\t\t\t\t\t</div>

\t\t\t\t\t<div
\t\t\t\t\t\tid=\"select-container-date\" class=\"input-container onWhite\">
\t\t\t\t\t\t<!-- Selection de l'heure de début du rdv -->
\t\t\t\t\t\t<label>Heure de début du parcours :
\t\t\t\t\t\t</label>
\t\t\t\t\t\t<input class=\"input-field\" type=\"time\" id=\"timeBegin\" name=\"timeBegin\" required>
\t\t\t\t\t</div>

\t\t\t\t\t<div class=\"modal-footer\">
\t\t\t\t\t\t<button type=\"button\" class=\"btn-consult btn-secondary\" data-bs-dismiss=\"modal\">Annuler</button>
\t\t\t\t\t\t<button type=\"submit\" class=\"btn-valide btn-secondary\" onclick=\"AddEventValider()\">Valider</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</form>
\t\t</div>
\t</div>
</div>

<input type=\"hidden\" id=\"material\" value='{{listMaterialResourceJSON.content}}'/>
<input type=\"hidden\" id=\"human\" value='{{listHumanResourceJSON.content}}'/>
<input type='hidden' id=\"listeAppointments\" name=\"listeAppointments\" value='{{listAppointmentsJSON.content}}'>
<input type=\"hidden\" id=\"listScheduledActivitiesJSON\" value='{{listScheduledActivitiesJSON.content}}'/>
<input id=\"date\" name=\"date\" type=\"hidden\" value={{currentdate}}>
<input id=\"listeSuccessors\" name=\"listeSuccessors\" type='hidden' value={{listSuccessorsJSON.content}}>
<input id=\"listeActivities\" name=\"listeActivities\" type='hidden' value={{listActivitiesJSON.content}}>
<input id=\"listeActivityHumanResource\" name=\"listeActivityHumanResource\" type=\"hidden\" value={{listActivityHumanResourcesJSON.content}}>
<input id=\"listeActivityMaterialResource\" name=\"listeActivityMaterialResource\" type='hidden' value={{listActivityMaterialResourcesJSON.content}}>
<input id=\"categoryOfMaterialResourceJSON\" name=\"categoryOfMaterialResourceJSON\" type='hidden' value={{categoryOfMaterialResourceJSON.content}}>
<input id=\"categoryOfHumanResourceJSON\" name=\"categoryOfHumanResourceJSON\" type='hidden' value={{categoryOfHumanResourceJSON.content}}>

{% for setting in settingsRepository %}
\t<input type=\"hidden\" name=\"modifAlertTime\" id=\"modifAlertTime\" value='{{setting.getAlertmodificationtimer}}'>
{% endfor %}

<div class=\"all-container\" style=\"display: flex; flex-direction: row;\">
<div style=\"width:100%;\">

\t<h1>Modification du Planning du {{ currentdate|date(\"d/m/Y\") }}</h1>
\t<div id=\"container-top\">
\t\t<div
\t\t\tclass=\"CRUD-div\">
\t\t\t<!-- bouton d'ajout d'un rendez-vous dans le planning -->
\t\t\t<button type=\"button\" class=\"btn-calendar-edit\" onclick=\"addEvent()\">Ajouter</button>

\t\t\t<!-- filtre de l'affichage par catégorie -->
\t\t\t<select class=\"btn-calendar-edit\" id=\"displayList\" onchange=\"changePlanning()\">
\t\t\t\t<option value=\"0\" selected disabled>Changer l'affichage
\t\t\t\t</option>
\t\t\t\t<option value=\"rh\">Ressources Humaines</option>
\t\t\t\t<option value=\"rm\">Ressources Matérielles</option>
\t\t\t</select>

\t\t\t<!-- bouton pour filtrer l'affichage (pas encore opérationnel) -->
\t\t\t<button id=\"filterbutton\" onclick=\"filterShow()\" class=\"btn-calendar-edit\" title=\"Filtrer l'affichage\">
\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" enable-background=\"new 0 0 24 24\" height=\"24px\" viewbox=\"0 0 24 24\" width=\"24px\">
\t\t\t\t\t<g><path d=\"M0,0h24 M24,24H0\" fill=\"none\"/><path d=\"M4.25,5.61C6.27,8.2,10,13,10,13v6c0,0.55,0.45,1,1,1h2c0.55,0,1-0.45,1-1v-6c0,0,3.72-4.8,5.74-7.39 C20.25,4.95,19.78,4,18.95,4H5.04C4.21,4,3.74,4.95,4.25,5.61z\"/><path d=\"M0,0h24v24H0V0z\" fill=\"none\"/></g>
\t\t\t\t</svg>
\t\t\t</button>
\t\t\t<div id=\"filterId\" class=\"filter-container\" style=\"display:none\"></div>
\t\t</div>
\t\t
\t\t<!-- liste des boutons pour annuler ou valider les modifications -->


\t\t<div id='container-top-right'>
\t\t\t<button type=\"button\" class=\"btn-calendar-edit\" onclick=\"undoEvent()\">Retour en Arrière</button>
\t\t\t<button type=\"button\" class=\"btn-delete btn-secondary\" onclick=\"window.location.assign('/ModificationDeleteOnUnload?dateModified=' + \$_GET('date') + '&id=' + \$_GET('id'))\">Annuler</button>
\t\t\t{% if app.user %}
\t\t\t\t{% set userIdentifier = app.user.getUserIdentifier() %}
\t\t\t{% else %}
\t\t\t\t{% set userIdentifier = '' %}
\t\t\t{% endif %}
\t\t\t<form method=\"post\" action=\"{{ path('ModificationPlanningValidation', { username: userIdentifier }) }}\">
\t\t\t\t<button type=\"submit\" class=\"btn-edit btn-secondary\" onclick=\"setEvents()\">Valider</button>
\t\t\t\t<input type=\"hidden\" id='events' name=\"events\"/>
\t\t\t\t<input type=\"hidden\" id='validation-date' name=\"validation-date\"/>
\t\t\t\t<input type=\"hidden\" id='list-resource' name=\"list-resource\"/>
\t\t\t</form>
\t\t</div>
\t</div>
\t  
\t<div
\t\tid=\"calendar-container\" class=\"calendar-container\">

\t\t<select name=\"zoom\" onclick=\"zoomChange()\" class=\"form-select\" id=\"zoom\" >
\t\t\t<option value=\"02:00:00\">Zoom : 2H</option>
\t\t\t<option value=\"01:00:00\">Zoom : 1H</option>
\t\t\t<option value=\"0:40:00\">Zoom : 40min</option>
\t\t\t<option value='00:20:00' selected>Zoom : 20min (Default)</option>
\t\t\t<option value=\"00:10:00\">Zoom : 10min</option>
\t\t\t<option value=\"00:05:00\">Zoom : 5min</option>
\t\t\t<option value=\"00:03:00\">Zoom : 1min</option>
\t\t</select>

\t\t<!-- affichage du calendar -->
\t\t<div id='calendar'></div>
\t</div>
\t</div>

\t<div id=\"lateral-panel\">
\t\t<input id=\"lateral-panel-input\" type=\"checkbox\" onclick=\"displayListErrorMessages()\">
\t\t<label id=\"lateral-panel-label\" for=\"lateral-panel-input\"></label>
\t\t<div id=\"lateral-panel-bloc\">
\t\t\t<div style='text-align:center'>
\t\t\t\t<h2>Liste des erreurs de Plannification</h2>
\t\t\t</div>
\t\t</div>
\t</div>
</div>


<script>alertOnload();</script>
{% endblock %}
", "planning/modification-planning.html.twig", "C:\\Users\\mdpVirgile\\Documents\\GitHub\\Projet_Stage_DI4\\templates\\planning\\modification-planning.html.twig");
    }
}
