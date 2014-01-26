<?
	class ToolbarEntry {
		var $id, $parent;

		var $title;
		var $url;
		/* type of node -- 'directory', 'link' */
		var $type;
		var $description;

		/* text/link properties */
		var $link_prefix;
		var $link_suffix;
		var $title_prefix;
		var $title_suffix;

		/* background color properties */
		var $fgColor, $bgColor;

		/* class tag */
		var $class;

		/* boolean selected/unselected */
		var $selected;

		/* constructor
			Creates a new entry for use in Toolbar class... 
		*/
		function ToolbarEntry($id, $parent, $type, $title, $url) {
			$this->id = $id;
			$this->parent = $parent;
			$this->type = $type;
			$this->title = $title;
			$this->url = $url;

			$this->selected = 0;

			$this->link_formatting();
			$this->title_formatting();
			$this->body_formatting();
			$this->style_tag();
		}

		function display($depth = 0, $spacer = 1, $fgColor = 0, $bgColor = 0) {
			$space = str_repeat("&nbsp; ", $depth - 1);

			if ($fgColor || $bgColor) {
				echo "<FONT ";
				if ($fgColor) echo "COLOR=\"$fgColor\"";
				if ($bgColor) echo " STYLE=\"background-color: $bgColor;\"";
				echo ">";
			}

			if ($this->selected) {
				$selStart = "<B>";
				$selEnd = "</B>";
			}


			if (!empty($this->url)) {
				$class = (!empty($this->class)) ? " CLASS=\"$this->class\" " : " ";

				echo $this->link_prefix . $space . $selStart . "<A" . $class . "HREF=\"$this->url\">" 
					. $this->title_prefix . $this->title . $this->title_suffix . "</A>" . $selEnd . $this->link_suffix;

			} else {
				echo $this->link_prefix . $space . "<B>$this->title</B>" . $extra . $this->link_suffix;
			}

			if ($fgColor || $bgColor) {
				echo "</FONT>";
			}
		}

		function selected($sel) {
			$this->selected = $sel;
		}

		function link_formatting($before = "", $after = "") {
			$this->link_prefix = $before;
			$this->link_suffix = $after;
		}

		function title_formatting($before = "", $after = "") {
			$this->title_prefix = $before;
			$this->title_suffix = $after;
		}

		function body_formatting($bg = "#FFFFFF", $fg = "#000000") {
			$this->fgColor = $fg;
			$this->bgColor = $bg;
		}

		function style_tag($class = "") {
			$this->class = $class;
		}

	}

	class Toolbar {

		var $toolbarTitle;

		var $iterate_counter;

		/* this variable should always contain the id of the last node added (directory or link) */
		var $lastNode;

		/* internal entry counter */
		var $entryCounter;

		/* toolbar entry data structure */
		var $toolbarEntries;

		var $fgColors, $bgColors;

		/* constructor
			always returns 0;
		*/
		function Toolbar($title = "") {
			$this->title($title);
			$this->entryCounter = 0;
			$this->toolbarEntries = array();
			$this->iterate_counter = 0;
			return 0;
		}

		/* set/unset the toolbar title
		*/
		function title($toolbarTitle = "") {
			$this->toolbarTitle = (!empty($toolbarTitle)) ? $toolbarTitle : "";
		}


		/* create a new "directory", every node can have a parent value
		   but for simplicity, a directory contains children (links)
			returns new id of directory and updates lastNode;
		*/
		function directory($parent, $name, $url = "") {
			$entryCounter = ++$this->entryCounter;

			$this->toolbarEntries[$entryCounter] = new ToolbarEntry($entryCounter, $parent, 'directory', $name, $url);
			$this->lastNode = $entryCounter;

			return $entryCounter;
		}

		/* create a new link under "directory" parent.

		*/
		function link($parent, $name, $url = "") {
			$entryCounter = ++$this->entryCounter;

			$this->toolbarEntries[$entryCounter] = new ToolbarEntry($entryCounter, $parent, 'link', $name, $url);
			$this->lastNode = $entryCounter;

			return $entryCounter;
		}

		/*
			display toolbar starting at node $node, with depth $depth
			defaults to root with a depth of 3
		*/
		function display($node = 0, $mdepth = 3) {
			$depth = 0;
			$maxDepth = $mdepth;

			echo (!empty($this->toolbarTitle)) ? "<B>$this->toolbarTitle</B><BR>" : "";
			
			$this->recurseNodes($this, $node, $depth, $maxDepth);
		}

		/* sets various properties on a link
		*/
		function link_properties($nid, $l_prefix = "", $l_suffix = "", $t_prefix = "", $t_suffix = "", $l_class = "") {
			if (!is_object($this->toolbarEntries[$nid])) {
				return 0;
			}

			$this->toolbarEntries[$nid]->link_formatting($l_prefix, $l_suffix);
			$this->toolbarEntries[$nid]->title_formatting($t_prefix, $t_suffix);
			$this->toolbarEntries[$nid]->style_tag($l_class);
		}


		/* function to set colors depths (levels)
			foreground colors
		*/
		function setFgColors() {
			$fgColors = func_get_args();
			$this->fgColors = $fgColors;
		}



		/* function to set color depths (levels)
			bg colors
		*/
		function setBgColors() {
			$bgColors = func_get_args();
			$this->bgColors = $bgColors;
		}


		/* function to make link selected (bold */
		function selected($nid, $sel = 1) {
			if (!is_object($this->toolbarEntries[$nid])) {
				return 0;
			}

			$this->toolbarEntries[$nid]->selected($sel);
		}


		/* function that takes another instance of class Toolbar 
			and inserts it into the current toolbar
		*/

		function insertToolbar($startNode, $newToolbar) {
			if (!is_object($newToolbar)) return 0;

			$depth = 0;
			$maxDepth = 100;

			/* what is the deal with this trickery!!!!
				it'd be awesome if PHP could use static variables in classes =)

				or simply ignore the laws of recursion
			*/

			$GLOBALS['toolbarClass_counter420'] = $this->entryCounter;
			$GLOBALS['toolbarClass_toolbarEntries'] = $this->toolbarEntries;

			$this->recurseInsertNodes($newToolbar, 0, $depth, $maxDepth, $startNode);

			$this->entryCounter = $GLOBALS['toolbarClass_counter420'];
			$this->toolbarEntries = $GLOBALS['toolbarClass_toolbarEntries'];
		}

		/* ----------- PRIVATE FUNCTIONS ----------- */

		/* function to recurse nodes in $toolbar and insert them into $this
		*/
		function recurseInsertNodes($toolbar, $parent, $depth, $maxDepth, $insertUnder) {
			if (!is_object($toolbar)) return 0;

			$depth++;
			$nodes = $toolbar->getNodes($toolbar, $parent, $depth, $maxDepth);
			$c = count($nodes);
			if ($depth  > $maxDepth) return 0;

			$lastNode = 0;
			$lastDepth = 0;

			$insertParent = $insertUnder;
			$toolbarEntries = &$GLOBALS['toolbarClass_toolbarEntries'];

			for($i=0; $i<$c; $i++) {
				$nodei = $nodes[$i];

				/* CRUCIAL TODO
					maintain relation for inserted toolbar, right now it only puts all the nodes at the same depth
				*/

				$GLOBALS['toolbarClass_counter420']++;
				

				$nodeid = $this->addNode($GLOBALS['toolbarClass_counter420'], 
						$insertParent, $toolbar->toolbarEntries[$nodei]->title, $toolbar->toolbarEntries[$nodei]->url, 
						$toolbar->toolbarEntries[$nodei]->type);

				/* inheirit style properties as well */
				$toolbarEntries[$nodeid]->link_prefix = $toolbar->toolbarEntries[$nodei]->link_prefix;
				$toolbarEntries[$nodeid]->link_suffix = $toolbar->toolbarEntries[$nodei]->link_suffix;

				$toolbarEntries[$nodeid]->title_prefix = $toolbar->toolbarEntries[$nodei]->title_prefix;
				$toolbarEntries[$nodeid]->title_suffix = $toolbar->toolbarEntries[$nodei]->title_suffix;

				$toolbarEntries[$nodeid]->class = $toolbar->toolbarEntries[$nodei]->class;

				$toolbarEntries[$nodeid]->fgColor = $toolbar->toolbarEntries[$nodei]->fgColor;
				$toolbarEntries[$nodeid]->bgColor = $toolbar->toolbarEntries[$nodei]->bgColor;

				$toolbarEntries[$nodeid]->selected = $toolbar->toolbarEntries[$nodei]->selected;

				$lastNode = $nodei;

				$lastDepth = $depth;


				/* continue recursing */
				$toolbar->recurseInsertNodes($toolbar, $nodei, $depth, $maxDepth, $insertParent);
			}
		}



		/* add a node using this stupid globals variable trick.. 
		*/
		function addNode($id, $parent, $name, $url, $type) {
			$toolbarEntries = &$GLOBALS['toolbarClass_toolbarEntries'];
			$toolbarEntries[$id] = new ToolbarEntry($id, $parent, $type, $name, $url);
			$this->lastNode = $id;
			return $id;
		}


		/* gets an array containing all ids for nodes which have parent $parent...
		*/
		function getNodes($toolbar, $parent = 0) {
			if (!is_object($toolbar)) return 0;

			$rv = array();
			for($i=1; $i<=$toolbar->entryCounter; $i++) {

				if ($parent == $toolbar->toolbarEntries[$i]->parent) {
					$rv[] = $toolbar->toolbarEntries[$i]->id;
				}
			}
			return $rv;
		}

		/* function to recurse nodes and write up the html 
		*/
		function recurseNodes($toolbar, $parent, $depth, $maxDepth) {
			$depth++;
			$nodes = $toolbar->getNodes($toolbar, $parent, $depth, $maxDepth);
			$c = count($nodes);
			if ($depth  > $maxDepth) return;
			for($i=0; $i<$c; $i++) {
				$nodei = $nodes[$i];

				/* display individual entry */
				if ($lastDepth < $depth) {
					$toolbar->toolbarEntries[$nodei]->display($depth, 1, $toolbar->fgColors[$depth], $toolbar->bgColors[$depth]);
				} else {
					$toolbar->toolbarEntries[$nodei]->display($depth, $toolbar->fgColors[$depth], $toolbar->bgColors[$depth]);
				}

				/* continue recursing */
				$toolbar->recurseNodes($toolbar, $nodei, $depth, $maxDepth);

				$lastDepth = $depth;
			}
		}

		function iterate() {
			if ($this->iterate_counter++ > $this->entryCounter) {
				$this->iterate_counter = 0;
				return FALSE;
			}
			return $this->toolbarEntries[$this->iterate_counter];
		}


	}
?>
