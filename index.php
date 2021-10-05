<?php
    /**
     * @author CARLOS EDUARDO REBECCHI
     */

   /**
   * test list
   */ 
   $list = [
    '{[(]}',
    '{[()}',
    '{[()]}',
    '{]}',
    '[()]}',
    '[()]',
    '[(]',
    '[]',
    '(){}[]',
    '[{()}](){}',
    '[]{()',
    '[{)]',
    ];
?>
<html>
<head>
    <style>
    table, th, td {
    border: 1px solid black;
    }
    table.center {
    margin-left: auto;
    margin-right: auto;
    }
    </style>
</head>
<body>
    <table style="border: 1px solid black;">
        <thead>
            <tr>
                <th>Value</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $k => $v): ?>
            <tr>
                <td><?= $v ?></td>
                <td><?= BalancedBrackets::balance($v)?'true':'false' ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

<?php
    class BalancedBrackets{
    
        /**
         * Performance arrays, enabling future combinations
         */
        private static $association = [
            '(' => ['{' => true, '[' => true, '(' => true, '}' => false, ']' => false, ')' => true],
            '[' => ['{' => true, '[' => true, '(' => true, '}' => false, ']' => true, ')' => false],
            '{' => ['{' => true, '[' => true, '(' => true, '}' => true, ']' => false, ')' => false],
        ];

        /**
         * exact match
         */
        private static $match = [
            '(' => ')',
            '[' => ']',
            '{' => '}',
        ];

        /**
         * starting construction
         * @param $test string
         * @return bool
         */
        public static function balance($test): bool
        {
            $bBattery = str_split($test);
            $valid = self::build([], $bBattery, $test);
            return $valid;
        }
    
        /**
         * search through the list to balance
         * @param $a array
         * @param $b array
         * @return bool
         */
        private static function build($a, $b, $t = null): bool
        {
            $return  = true;
            if(self::conciliate($a, $b)){
                $return =  self::build($a, $b);
            }
            if(count($b) > 0){
                $a[] = current($b);
                array_splice($b, key($b), 1);
            }
            if(!empty($a) && empty($b)){
                $return = false;
            }
            if( $return && !empty(self::$association[end($a)])){
                if( self::$association[end($a)][current($b)] ){
                    $return =  self::build($a, $b, $t);
                        
                }else{
                    $return = false;
                }
            }
            return $return;
        }

        /**
         * find and balance
         * @param $a array
         * @param $b array
         * @return bool
         */
        private static function conciliate(&$a, &$b): ?bool
        {
            if(!empty(self::$match[end($a)]) && self::$match[end($a)] == current($b) ){
                array_splice($a, key($a), 1);
                array_splice($b, key($b), 1);
                self::conciliate($a, $b);
                return true;
            }
            return false;
        }
    }
?>