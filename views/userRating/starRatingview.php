<?php if ($sr_bp_hasmembers) : ?>

    <div id="pag-top" class="pagination no-ajax">

        <div class="pag-count" id="member-dir-count-bottom">

            <?php  bp_members_pagination_count(); ?>

        </div>

        <div class="pagination-links" id="member-dir-pag-bottom">

            <?php  bp_members_pagination_links(); ?>

        </div>

    </div>
    <?php do_action('bp_before_directory_members_list'); ?>
    <form action="#" method="post" id="sr-save-reward-form">
        <input type="submit" class="generic-button">
        <table id="member-list" class="notifications">
            <thead>
            <tr>
                <th>Student List</th>
                <th>Reward</th>
                <th>Last Action Date</th>
            </tr>
            </thead>
            <tbody>

            <?php while (bp_members()) : bp_the_member(); ?>
                <tr>
                    <?php $sr_uid = bp_get_member_user_id(); ?>

                    <td>
                        <a href="<?php bp_member_permalink(); ?>"><?php bp_member_user_nicename(); ?></a>
                    </td>

                    <td>

                        <label for="sr-reward-radio-3-<?php echo $sr_uid ?>"><input type="radio"
                                                                                    name="<?php echo $sr_uid ?>"
                                                                                    value="none"
                                                                                    id="sr-reward-radio-3-<?php echo $sr_uid ?>" checked>None</label>
                        <label for="sr-reward-radio-2-<?php echo $sr_uid ?>"><input type="radio"
                                                                                    name="<?php echo $sr_uid ?>"
                                                                                    value="silver"
                                                                                    id="sr-reward-radio-2-<?php echo $sr_uid ?>" <?php if (get_user_meta($sr_uid, 'sr_reward', true) == 'silver') echo 'checked'; ?>>Silver</label>
                        <label for="sr-reward-radio-1-<?php echo $sr_uid ?>"><input type="radio"
                                                                                    name="<?php echo $sr_uid ?>"
                                                                                    value="gold"
                                                                                    id="sr-reward-radio-1-<?php echo $sr_uid ?>" <?php if (get_user_meta($sr_uid, 'sr_reward', true) == 'gold') echo 'checked'; ?>>Gold</label>

                    </td>

                    <td class="sr_last_action" id="sr_last_action<?php echo $sr_uid; ?>">

                        <?php
                        $last_update_date = get_user_meta($sr_uid, 'sr_last_action_date', true);
                        if (isset($last_update_date))
                            echo $last_update_date; ?>

                    </td>

                </tr>

            <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <input type="submit" class="generic-button">
    </form>


    <?php do_action('bp_after_directory_members_list'); ?>

    <div id="pag-bottom" class="pagination no-ajax">

        <div class="pag-count" id="member-dir-count-bottom">

            <?php  bp_members_pagination_count(); ?>

        </div>

        <div class="pagination-links" id="member-dir-pag-bottom">

            <?php  bp_members_pagination_links(); ?>

        </div>

    </div>
<?php else: ?>
    <div id="message" class="info">
        <p><?php _e("Sorry, no members were found." , 'User-Star-Rating'); ?></p>
    </div>
<?php endif; ?>
