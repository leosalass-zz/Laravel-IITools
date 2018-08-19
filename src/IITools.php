<?php

namespace Immersioninteractive\ToolsController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class IITools extends Controller
{
    public static $base_image_path = 'uploads/images/';

    public static function file_upload($input_name, $target_dir, $file_name, $file_limit_in_KB = null, $override = false)
    {
        self::directories($target_dir);

        try {
            $target_file = self::$base_image_path . $target_dir . DIRECTORY_SEPARATOR . $file_name;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES[$input_name]["tmp_name"]);
            if ($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                //echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if ($override) {
                if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                }
            }
            // Check file size
            if ($file_limit_in_KB > 0) {
                if ($_FILES["fileToUpload"]["size"] > self::file_upload_max_size()
                    || $_FILES["fileToUpload"]["size"] > $file_limit_in_KB) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
            } else {
                if ($_FILES["fileToUpload"]["size"] > self::file_upload_max_size()) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
            } else {
                //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    //echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
                    return $target_file;
                } else {
                    //echo "Sorry, there was an error uploading your file.";
                    return null;
                }
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function directories($directory_path)
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

        return $base_path;
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
            "getQualifiedDeletedAtColumn",
        ];

        foreach ($removeKeys as $del_val) {

            if (($key = array_search($del_val, $array)) !== false) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function log_request($fichero, $data_array)
    {
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

            foreach ($keys as $key) {
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

    // Returns a file size limit in bytes based on the PHP upload_max_filesize
    // and post_max_size
    public static function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = self::parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = self::parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    public static function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}
