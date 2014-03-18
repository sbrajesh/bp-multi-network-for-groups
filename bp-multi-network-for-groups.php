<?php

/**
 * Plugin Name: Bp Multinetwork for Groups Only
 * Author: Brajesh Singh
 * Version: 1.0
 * Description: Allows to have different instance of Groups on all of the subsites of a multinetwork
 */


//only filter group tables
class BP_MultiNetwork_For_Groups{
    
    
    private static $instance;
    
    private function __construct() {
            
            add_filter( 'pre_update_site_option_bp-db-version', array( $this, 'filter_bpdb_update_version' ), 10, 3 );
            //use get_option instead of get_site_option for bpdb version
            add_filter( 'site_option_bp-db-version', array( $this, 'filter_bpdb_get_version' ) );
            add_filter( 'bp_groups_global_tables',  array( $this, 'filter_groups_tables' ) );
    }
    
    public static function get_instance(){
        
        if( !isset( self::$instance ) )
            self::$instance = new self();
        return self::$instance;
        
    }
//filter bp-db-version and use get_option insdead of get_site_option
//this will force bp to consider each blog as having their own db
    public function filter_bpdb_get_version( $val ) {

        $version = get_option( 'bp-db-version' );
        return $version;
    }

//filter update site option to save the bp-db-version in blog meta and not in the site meta, it will make it per blog instead of per MS install
    public function filter_bpdb_update_version( $value, $oldvalue ) {
        update_option( 'bp-db-version', $value );
        return $value;
    }
    public function filter_groups_tables( $tables ){
        global $wpdb;
        $prefix = $wpdb->prefix;

        $tables = array(
                        'table_name'           => $prefix . 'bp_groups',
                        'table_name_members'   => $prefix . 'bp_groups_members',
                        'table_name_groupmeta' => $prefix . 'bp_groups_groupmeta'
            );

        return $tables;
    }
}
BP_MultiNetwork_For_Groups::get_instance();