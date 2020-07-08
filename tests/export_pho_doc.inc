<?
// *** Paramétrage du module eximport - export_balise_exemple ***
// Modifié le 23/09/2014 par le module de paramétrage Cadic Intégrale

$Param['MODE_EXIMPORT'] = "EXPORT";
$ParamLibelle['MODE_EXIMPORT'] = "(IMPORT/EXPORT) IMPORT:(par défaut) utiliser le module pou importer, EXPORT:utiliser le module pour exporter les données d'une table";

$Param['TABLE'] = "PHO_DOC";
$ParamLibelle['TABLE'] = "Nom de la table Cadic Intégrale";

$Param['FORMAT'] = "ASCIIDEL";
$ParamLibelle['FORMAT'] = "Structure du fichier de metadonnées à importer : VARIABLE(balisé), ASCIIDEL, ISO2709";

$Param['REP_GED'] = "";
$ParamLibelle['REP_GED'] = "";

$Param['EXPORT_RESTRICT_INIT'] = "DOC_ANALYSE=1 and LCK_CLI NOT LIKE 'SUPPR'";
$ParamLibelle['EXPORT_RESTRICT_INIT'] = "Condition pour exporter les notices et les documents/images (par exemple LCK_CLI not LIKE 'SUPPR')";

$Param['EXPORT_LISTE_CHAMPS'] = "DOC_TYPE,DOC_TITRE,DOC_LANGUE,DOC_COMMENT,CND_OEUVRE,DOC_AUTEUR,DOC_AUTEURSEC,DOC_AUTMORAL,PHO_RESP_EDIT,DOC_DP,DOC_DP_STAT,DOC_DEE,PHO_PERSON,DOC_GEO,PHO_HISTORI,CND_COLL_AU,DOC_COLLECTION,PHO_ORIGINE,PHO_COPYRIGHT,PHO_DROIT_IMG,DOC_SUP,PHO_COULEUR,PHO_FORMAT,PHO_PIXEL,PHO_FORMFILE,CND_DUREE,PHO_NB_DOC,DOC_COTE,PHO_NUMCD,PHO_NUMPLAN,PHO_LOC_CD,CND_COTE_P,PHO_LOCDIAORI,IMG_REF,DOC_ATTACHE,AUD_REF,CND_VIDEO_REF,CND_VIDEO_REF1,CND_URL,DOC_REF,DATM,DOC_ANALYSE,DOC_AFFICHE";
$ParamLibelle['EXPORT_LISTE_CHAMPS'] = "liste des champs à exporter séparés par des virgules (si vide ou * : le module exporte tous les champs hors litéraux)";

$Param['SEPARENREG'] = "";
$ParamLibelle['SEPARENREG'] = "séparateur d'enregistrements (par défaut , nouvelle ligne)";

$Param['SEPAR'] = ";";
$ParamLibelle['SEPAR'] = "séparateur de champs (par défaut : tabulation)";

$Param['SEPARM'] = "|";
$ParamLibelle['SEPARM'] = "séparateur d'occurrences dans les champs multioccurrents";

$Param['ENCADRECHAMP'] = "";
$ParamLibelle['ENCADRECHAMP'] = "ajout d'une séquence autour de chaque contenu de champ. Par exemple le caractère guillemet simple (')";

$Param['LIBELLES_NOMSCHAMPS'] = "0";
$ParamLibelle['LIBELLES_NOMSCHAMPS'] = "Nommer les champs à partir de leurs leurs libellés, plutôt que des mmnémoniques internes de la table";

$Param['CHARSET_EXPORT'] = "UTF-8";


?>