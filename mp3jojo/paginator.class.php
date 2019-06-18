<?php

class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $default_ipp = 1000;
	var $querystring;

	function Paginator()
	{
		$this->current_page = 1;
		$this->mid_range = 5;
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
	}

	function paginate()
	{
		//echo "page: ".$_GET['page'];
		if($_GET['ipp'] == 'All')
		{
			$this->num_pages = ceil($this->items_total/$this->default_ipp);
			$this->items_per_page = $this->default_ipp;
		}
		else
		{
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total/$this->items_per_page);
			if ($this->num_pages <= 0)
				$this->num_pages = 1;
		}
		$this->current_page = (int) $_GET['page']; // must be numeric > 0
		if($this->current_page < 1 OR !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

		if($_GET)
		{
			$args = explode("&",$_SERVER['QUERY_STRING']);
			foreach($args as $arg)
			{
				$keyval = explode("=",$arg);
				if($keyval[0] != "page" And $keyval[0] != "ipp") $this->querystring .= "&".$arg;
			}
		}

		if($_POST)
		{
			foreach($_POST as $key=>$val)
			{
				if($key != "page" And $key != "ipp") $this->querystring .= "&$key=$val";
			}
		}

		$this->return = "<ul>";
		if($this->num_pages > 10)
		{
			$this->return .= ($this->current_page != 1 And $this->items_total >= 10) ? "<li><a href=\"$_SERVER[PHP_SELF]?$this->querystring&page=$prev_page\">&laquo; Previous</a></li>":"<li class=\"disabled\"><a href=\"#\">&laquo; Previous</a></li> ";

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= "<li class='disabled'><a href='#'>...</a></li>";
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					$this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? 
						"<li class=\"active\"><a title=\"Jump to page $i of $this->num_pages\" href=\"#\">$i</a></li> ":
						"<li><a title=\"Jump to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?$this->querystring&page=$i\">$i</a></li> ";
				}
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= "<li class='disabled'><a href='#'>...</a></li>";
			}
			$this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<li><a href=\"$_SERVER[PHP_SELF]?$this->querystring&page=$next_page\">Next &raquo;</a></li>\n":"<li class=\"disabled\"><a href=\"#\">&raquo; Next</a></li>\n";
		}
		else
		{
			#$this->return .= "<li><a href='#' class='disabled'>Halaman: </a></li>";
			for($i=1;$i<=$this->num_pages;$i++)
			{
				$this->return .= ($i == $this->current_page) ? "<li class=\"active\"><a href=\"#\">$i</a></li> ":"<li><a href=\"$_SERVER[PHP_SELF]?$this->querystring&page=$i\">$i</a></li> ";
			}
		}

		$this->return .= "</ul>";

		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = ($_GET['ipp'] == 'All') ? " LIMIT 1,1000":" LIMIT $this->low,$this->items_per_page";
	}

	function display_pages()
	{
		return $this->return;
	}
}