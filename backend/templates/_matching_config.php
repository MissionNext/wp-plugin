<?php
/**
 * @var Array $mainFields
 * @var Array $secondaryFields
 * @var Array $defaults
 * @var String $mainRole
 * @var String $secondaryRole
 */

$mainRoleLabel = ucfirst($mainRole) . ' fields';
$secondaryRoleLabel = ( 'organization' == $secondaryRole ? 'Receiving Organization' : 'Job' ) . ' fields';
?>

<form method="POST" action="<?php echo $_SERVER['REQUEST_URI'] ?>">

    <table class="relations wp-list-table widefat" >
        <thead>
            <tr>
                <th><?php echo $mainRoleLabel ?></th>
                <th><?php echo $secondaryRoleLabel ?></th>
                <th>Weight</th>
                <th>Relation type</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($defaults as $key=> $row): ?>
            <tr>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][main_field_id]" value="<?php echo $row['main_field']['id'] ?>"/>
                    <span><?php echo $row['main_field']['name'] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][matching_field_id]" value="<?php echo $row['matching_field']['id'] ?>"/>
                    <span><?php echo $row['matching_field']['name'] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][weight]" value="<?php echo $row['weight'] ?>"/>
                    <span><?php echo \MissionNext\lib\Constants::$matchingWeights[$row['weight']] ?></span>
                </td>
                <td>
                    <input type="hidden" name="mn_rels[<?php echo $key ?>][matching_type]" value="<?php echo $row['matching_type'] ?>"/>
                    <span><?php echo \MissionNext\lib\Constants::$matchingTypes[$row['matching_type']] ?></span>
                </td>
                <td>
                    <button type="button" class="delete button">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <select id="mainFields">
                        <?php foreach($mainFields as $mainField): ?>
                            <option value="<?php echo $mainField['id'] ?>"><?php echo $mainField['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="secondaryFields">
                        <?php foreach($secondaryFields as $secondaryField): ?>
                            <option value="<?php echo $secondaryField['id'] ?>"><?php echo $secondaryField['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="weights">
                        <?php foreach(\MissionNext\lib\Constants::$matchingWeights as $key => $label): ?>
                        <option value="<?php echo $key ?>"><?php echo $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select id="relationType">
                        <?php foreach(\MissionNext\lib\Constants::$matchingTypes as $key => $label): ?>
                            <option value="<?php echo $key ?>"><?php echo $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <button type="button" class="button" id="relationAdd"><?php echo 'Add' ?></button>
                </td>
            </tr>
        </tfoot>
    </table>

    <button type="submit" class="button button-primary"><?php echo 'Save' ?></button>

</form>

<script>
    jQuery(document).ready(function(){
        jQuery(document).on('click', '#relationAdd', function(e){
            var mainFieldOption = jQuery('#mainFields option:selected');
            var secondaryFieldOption = jQuery('#secondaryFields option:selected');
            var weightOption = jQuery('#weights option:selected');
            var relationTypeOption = jQuery('#relationType option:selected');

            addRelation(
                mainFieldOption.val(),
                mainFieldOption.text(),
                secondaryFieldOption.val(),
                secondaryFieldOption.text(),
                weightOption.val(),
                weightOption.text(),
                relationTypeOption.val(),
                relationTypeOption.text()
            );
        }).on('click', 'table tr td button.delete', function(e){
            jQuery(e.target).parents('tr').remove();
        });
    });

    function addRelation(mainId, mainLabel, matchId, matchLabel, weight, weightLabel, type, typeLabel){

        if(!mainId || !matchId){
            return;
        }

        var container = jQuery('.relations tbody');

        var key = new Date().getTime();

        var row = jQuery(document.createElement('tr'));
        row.append('<td><input type="hidden" name="mn_rels['+key+'][main_field_id]" value="' + mainId + '"><span>' + mainLabel + '</span></td>');
        row.append('<td><input type="hidden" name="mn_rels['+key+'][matching_field_id]" value="' + matchId + '"><span>' + matchLabel + '</span></td>');
        row.append('<td><input type="hidden" name="mn_rels['+key+'][weight]" value="' + weight + '"><span>' + weightLabel + '</span></td>');
        row.append('<td><input type="hidden" name="mn_rels['+key+'][matching_type]" value="' + type + '"><span>' + typeLabel+ '</span></td>');
        row.append('<td><button type="button" class="button delete">Delete</button></td>');

        container.append(row);
    }
</script>