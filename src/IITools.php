<?php

namespace Immersioninteractive\ToolsController;

use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class IITools extends Controller
{
    public static $base_image_path = 'uploads/images/';

    public static function single_base64_upload($base64_string, $directory_path = null)
    {
        if($directory_path == null){
            $directory_path = 'defaults';
        }

        $extension = 'jpg';
        $file_name = date("Ymdhis") . rand(11111, 99999);
        $full_name = "$file_name.$extension";
        $url = URL::to('/') . DIRECTORY_SEPARATOR . self::$base_image_path . $directory_path . DIRECTORY_SEPARATOR . $full_name;

        $base_directories = explode('/', self::$base_image_path . $directory_path);
        $base_path = '';
        foreach ($base_directories as $directory_name) {
            $base_path .= $directory_name . '/';
            if (!file_exists($base_path)) {
                mkdir($base_path);
            }
        }

        // open the output file for writing
        $ifp = fopen(public_path($base_path . DIRECTORY_SEPARATOR . $full_name), 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);
        
        $response = [
            'url' => $url,
            'filename' => $full_name,
            'path' => DIRECTORY_SEPARATOR . self::$base_image_path . $directory_path,
        ];

        return $response;
    }

    public static function base64_to_file($base64_string, $output_file, $directory_path = 'default')
    {
        $dir_array = explode('/', $directory_path);

        $base_directories = ['uploads', 'images'];
        $base_path = '';
        foreach ($base_directories as $directory_name) {
            $base_path .= $directory_name . '/';
            if (!file_exists($base_path)) {
                mkdir($base_path);
            }
        }

        $current_dirname = '';
        foreach ($dir_array as $directory_name) {
            $current_dirname .= $directory_name . '/';
            if (!file_exists($base_path . $current_dirname)) {
                mkdir($base_path . $current_dirname);
            }
        }

        if (!file_exists(self::$base_image_path . $directory_path)) {
            mkdir(self::$base_image_path . $directory_path);
        }

        $path = $base_path . $directory_path;

        // open the output file for writing
        $ifp = fopen(public_path($path . DIRECTORY_SEPARATOR . $output_file), 'wb');

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);

        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[1]));

        // clean up the file resource
        fclose($ifp);

        return $output_file;
    }

    public static function rmdir($dirname)
    {
        try {
            Storage::deleteDirectory($dirname);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
        return true;
    }

    public static function remove_main_function_names($array)
    {
        $removeKeys = [
            'relation_names',
            'store',
            'update',
            'destroy',
            'middleware',
            'getMiddleware',
            'callAction',
            '__call',
            'authorize',
            'authorizeForUser',
            'parseAbilityAndArguments',
            'normalizeGuessedAbilityName',
            'authorizeResource',
            'resourceAbilityMap',
            'resourceMethodsWithoutModels',
            'dispatch',
            'dispatchNow',
            'validateWith',
            'validate',
            'extractInputFromRules',
            'validateWithBag',
            'getValidationFactory',

            "methods",
            "__construct",
            "bootIfNotBooted",
            "boot",
            "bootTraits",
            "clearBootedModels",
            "fill",
            "forceFill",
            "removeTableFromKey",
            "newInstance",
            "newFromBuilder",
            "on",
            "onWriteConnection",
            "all",
            "with",
            "load",
            "loadMissing",
            "increment",
            "decrement",
            "incrementOrDecrement",
            "incrementOrDecrementAttributeValue",
            "update",
            "push",
            "save",
            "saveOrFail",
            "finishSave",
            "performUpdate",
            "setKeysForSaveQuery",
            "getKeyForSaveQuery",
            "performInsert",
            "insertAndSetId",
            "destroy",
            "delete",
            "forceDelete",
            "performDeleteOnModel",
            "query",
            "newQuery",
            "newQueryWithoutScopes",
            "newQueryWithoutScope",
            "newEloquentBuilder",
            "newBaseQueryBuilder",
            "newCollection",
            "newPivot",
            "toArray",
            "toJson",
            "jsonSerialize",
            "fresh",
            "refresh",
            "replicate",
            "is",
            "isNot",
            "getConnection",
            "getConnectionName",
            "setConnection",
            "resolveConnection",
            "getConnectionResolver",
            "setConnectionResolver",
            "unsetConnectionResolver",
            "getTable",
            "setTable",
            "getKeyName",
            "setKeyName",
            "getQualifiedKeyName",
            "getKeyType",
            "setKeyType",
            "getIncrementing",
            "setIncrementing",
            "getKey",
            "getQueueableId",
            "getQueueableConnection",
            "getRouteKey",
            "getRouteKeyName",
            "resolveRouteBinding",
            "getForeignKey",
            "getPerPage",
            "setPerPage",
            "__get",
            "__set",
            "offsetExists",
            "offsetGet",
            "offsetSet",
            "offsetUnset",
            "__isset",
            "__unset",
            "__call",
            "__callStatic",
            "__toString",
            "__wakeup",
            "attributesToArray",
            "addDateAttributesToArray",
            "addMutatedAttributesToArray",
            "addCastAttributesToArray",
            "getArrayableAttributes",
            "getArrayableAppends",
            "relationsToArray",
            "getArrayableRelations",
            "getArrayableItems",
            "getAttribute",
            "getAttributeValue",
            "getAttributeFromArray",
            "getRelationValue",
            "getRelationshipFromMethod",
            "hasGetMutator",
            "mutateAttribute",
            "mutateAttributeForArray",
            "castAttribute",
            "getCastType",
            "setAttribute",
            "hasSetMutator",
            "isDateAttribute",
            "fillJsonAttribute",
            "getArrayAttributeWithValue",
            "getArrayAttributeByKey",
            "castAttributeAsJson",
            "asJson",
            "fromJson",
            "asDate",
            "asDateTime",
            "isStandardDateFormat",
            "fromDateTime",
            "asTimestamp",
            "serializeDate",
            "getDates",
            "getDateFormat",
            "setDateFormat",
            "hasCast",
            "getCasts",
            "isDateCastable",
            "isJsonCastable",
            "getAttributes",
            "setRawAttributes",
            "getOriginal",
            "only",
            "syncOriginal",
            "syncOriginalAttribute",
            "syncChanges",
            "isDirty",
            "isClean",
            "wasChanged",
            "hasChanges",
            "getDirty",
            "getChanges",
            "originalIsEquivalent",
            "append",
            "setAppends",
            "getMutatedAttributes",
            "cacheMutatedAttributes",
            "getMutatorMethods",
            "observe",
            "getObservableEvents",
            "setObservableEvents",
            "addObservableEvents",
            "removeObservableEvents",
            "registerModelEvent",
            "fireModelEvent",
            "fireCustomModelEvent",
            "filterModelEventResults",
            "retrieved",
            "saving",
            "saved",
            "updating",
            "updated",
            "creating",
            "created",
            "deleting",
            "deleted",
            "flushEventListeners",
            "getEventDispatcher",
            "setEventDispatcher",
            "unsetEventDispatcher",
            "addGlobalScope",
            "hasGlobalScope",
            "getGlobalScope",
            "getGlobalScopes",
            "hasOne",
            "morphOne",
            "belongsTo",
            "morphTo",
            "morphEagerTo",
            "morphInstanceTo",
            "getActualClassNameForMorph",
            "guessBelongsToRelation",
            "hasMany",
            "hasManyThrough",
            "morphMany",
            "belongsToMany",
            "morphToMany",
            "morphedByMany",
            "guessBelongsToManyRelation",
            "joiningTable",
            "touches",
            "touchOwners",
            "getMorphs",
            "getMorphClass",
            "newRelatedInstance",
            "getRelations",
            "getRelation",
            "relationLoaded",
            "setRelation",
            "setRelations",
            "getTouchedRelations",
            "setTouchedRelations",
            "touch",
            "updateTimestamps",
            "setCreatedAt",
            "setUpdatedAt",
            "freshTimestamp",
            "freshTimestampString",
            "usesTimestamps",
            "getCreatedAtColumn",
            "getUpdatedAtColumn",
            "getHidden",
            "setHidden",
            "addHidden",
            "getVisible",
            "setVisible",
            "addVisible",
            "makeVisible",
            "makeHidden",
            "getFillable",
            "fillable",
            "getGuarded",
            "guard",
            "unguard",
            "reguard",
            "isUnguarded",
            "unguarded",
            "isFillable",
            "isGuarded",
            "totallyGuarded",
            "fillableFromArray",
            "bootSoftDeletes",
            "runSoftDelete",
            "restore",
            "trashed",
            "restoring",
            "restored",
            "isForceDeleting",
            "getDeletedAtColumn",
            "getQualifiedDeletedAtColumn"
        ];

        foreach ($removeKeys as $del_val) {

            if (($key = array_search($del_val, $array)) !== false) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function log_request($fichero, $data_array){
        try {          

            if (!file_exists($fichero)) {
                $myfile = fopen($fichero, "w") or die("Unable to open file!");
                fwrite($myfile, "**** starts here ***** \n");
                fclose($myfile);
            }
            
            // Abre el fichero para obtener el contenido existente            
            $actual = file_get_contents($fichero);
            
            $log = "*****************************\n";
            $log .= 'time: ' . date('Y-m-d H:i:s') . "\n\n";
            //$log .= "***********************************************************\n\n";
            //$log .= "DATA ARRAY: " . json_encode($data_array) . "\n\n";
            
            $keys = array_keys($data_array);
            
            foreach($keys as $key){
                $content = (is_array($data_array[$key])) ? json_encode($data_array[$key]) : $data_array[$key];
                $log .= "$key: $content \n";
            }
            
            $log .= "\n\n\n";
            
            $log .= $actual;
            
            // Escribe el contenido al fichero
            file_put_contents($fichero, $log);
            
            return;
            
        } catch (\Exception $e) {
            /** TODO: check why the exception is not catching errors */
            file_put_contents($fichero, $e->getMessage());
        }
    }
}