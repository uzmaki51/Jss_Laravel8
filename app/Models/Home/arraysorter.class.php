<?php

/**
 * ２次元の配列をソートするクラス。
 *
 * @author K3
 * @since 2011/06/01
 */
class ArraySorter
{
	/**
	 * ソートキー
	 * 
	 * @var string
	 */
	static private $_sortKey = '';
	static private $_sortSubKey = '';
	
	/**
	 * ソートキーを設定する。
	 * 
	 * @access public
	 * @param string $sortKey
	 * @param string $sortSubKey
	 * @return string
	 */
    static public function setSortKey($sortKey, $sortSubKey = '')
	{
	    // main key
	    $prevKey = self::$_sortKey;
	    self::$_sortKey = $sortKey;
	    
	    // sub key
	    self::$_sortSubKey = $sortSubKey;

	    return $prevKey;
	}
	
	/**
	 * ソート（ASC）を行う。
	 * 
	 * @access public
	 * @param array $item1
	 * @param array $item2
	 * @return integer (-1, 0, 1) $item1 <=> $item2
	 */
	static public function sortASC($item1, $item2)
	{
	    if ($item1[self::$_sortKey] == $item2[self::$_sortKey]) {
	        // if equal, compare with sub key
	        if (empty(self::$_sortSubKey)) {
	            return 0;
	        }
	        if ($item1[self::$_sortSubKey] == $item2[self::$_sortSubKey]) {
	            return 0;
	        }
	        return ($item1[self::$_sortSubKey] < $item2[self::$_sortSubKey] ? -1 : 1);
	    }
	    return ($item1[self::$_sortKey] < $item2[self::$_sortKey] ? -1 : 1);
	}
	
	/**
	 * ソート（DESC）を行う。
	 * 
	 * @access public
	 * @param array $item1
	 * @param array $item2
	 * @return integer (1, 0, -1) $item1 <=> $item2
	 */
	static public function sortDESC($item1, $item2)
	{
	    return -self::sortASC($item1, $item2);
	}
}