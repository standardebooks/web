CREATE TABLE IF NOT EXISTS `Contributors` (
  `EbookId` int(10) unsigned NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UrlName` varchar(255) NOT NULL,
  `SortName` varchar(255) DEFAULT NULL,
  `WikipediaUrl` varchar(255) DEFAULT NULL,
  `MarcRole` enum('aut','ctb','ill','trl','edt','abr','acp','act','adi','adp','afd','aft','anc','anl','anm','ann','ant','ape','apl','app','aqt','arc','ard','arr','art','asg','asn','ato','att','auc','aud','aue','aui','aup','aus','bdd','bjd','bka','bkd','bkp','blw','bnd','bpd','brd','brl','bsl','cad','cas','ccp','chr','clb','cli','cll','clr','clt','cmm','cmp','cmt','cnd','cng','cns','coe','col','com','con','cop','cor','cos','cot','cou','cov','cpc','cpe','cph','cpl','cpt','cre','crp','crr','crt','csl','csp','cst','cte','ctg','ctr','cts','ctt','cur','cwt','dbd','dbp','dfd','dfe','dft','dgc','dgg','dgs','dis','djo','dln','dnc','dnr','dpc','dpt','drm','drt','dsr','dst','dtc','dte','dtm','dto','dub','edc','edd','edm','egr','elg','elt','eng','enj','etr','evp','exp','fac','fds','fld','flm','fmd','fmk','fmo','fmp','fnd','fon','fpy','frg','gdv','gis','grt','gst','his','hnr','hst','ilu','ink','ins','inv','isb','itr','ive','ivr','jud','jug','lbr','lbt','ldr','led','lee','lel','len','let','lgd','lie','lil','lit','lsa','lse','lso','ltg','ltr','lyr','mcp','mdc','med','mfp','mfr','mka','mod','mon','mrb','mrk','msd','mte','mtk','mup','mus','mxe','nan','nrt','onp','opn','org','orm','osp','oth','own','pad','pan','pat','pbd','pbl','pdr','pfr','pht','plt','pma','pmn','pnc','pop','ppm','ppt','pra','prc','prd','pre','prf','prg','prm','prn','pro','prp','prs','prt','prv','pta','pte','ptf','pth','ptt','pup','rap','rbr','rcd','rce','rcp','rdd','red','ren','res','rev','rpc','rps','rpt','rpy','rse','rsg','rsp','rsr','rst','rth','rtm','rxa','sad','sce','scl','scr','sde','sds','sec','sfx','sgd','sgn','sht','sll','sng','spk','spn','spy','srv','std','stg','stl','stm','stn','str','swd','tad','tau','tcd','tch','ths','tld','tlg','tlh','tlp','trc','tyd','tyg','uvp','vac','vdg','vfx','voc','wac','wal','wap','wam','wat','waw','wdc','wde','wfs','wft','wfw','win','wit','wpr','wst','wts') NOT NULL,
  `FullName` varchar(255) DEFAULT NULL,
  `NacoafUrl` varchar(255) DEFAULT NULL,
  `SortOrder` tinyint(3) unsigned NOT NULL,
  `IsDisplayed` tinyint(1) NOT NULL DEFAULT 1,
  `DeathYear` smallint(4) NULL,
  `IsDeathYearCirca` tinint(1) DEFAULT 0,
  KEY `EbookId` (`EbookId`),
  KEY `UrlName` (`UrlName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
