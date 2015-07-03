<!-- Title -->
<div id="shares-details-comments-header-div" class="container">
    <h3 ng-if="(share.comment_count == 0)">Aucun commentaire</h3>
    <h3 ng-if="(share.comment_count == 1)">1 commentaire</h3>
    <h3 ng-if="(share.comment_count > 1)">{{ share.comment_count }} commentaires</h3>
</div>

<!-- Comments -->
<div id="shares-details-comments-div" class="container">
    <div class="row">

        <div class="col-md-12">

            <div id="shares-details-comments-list-div" ng-if="(comments.length > 0)">
                <!-- All comments -->
                <div ng-repeat="comment in comments" class="shares-details-comment-div">

                    <!-- Creator comment -->
                    <div ng-if="(shareUserExternalId == comment.user.external_id)" class="media shares-details-comment-creator-div">
                        <div class="media-body">
                            <blockquote class="blockquote-reverse">

                                <p class="media-heading text-left">{{ comment.message }}</p>

                                <footer class="text-left">
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                        <div class="media-right text-center">
                            <img class="shares-details-comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                            {{ comment.user.username }}
                        </div>
                    </div>

                    <!-- Other user comment -->
                    <div ng-if="(shareUserExternalId != comment.user.external_id)" class="media shares-details-comment-other-user-div">
                        <div class="media-left text-center">
                            <img class="comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                            {{ comment.user.username }}
                        </div>
                        <div class="media-body">
                            <blockquote class="blockquote-normal">

                                <p class="media-heading text-left">{{ comment.message }}</p>

                                <footer class="text-left">
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <?php echo $this->element('pagination'); ?>
            </div>

            <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <!-- No comments -->
            <h3 ng-if="(comments.length == 0)" class="shares-details-comments-message-h3">
                <small>Soyez le premier à commenter ce partage</small>
            </h3>

            <?php else : ?>

            <!-- Not authenticated -->
            <h3 ng-if="(comments.length == 0)" class="shares-details-comments-message-h3">
                <small><a class="authenticate-button" href="#">Authentifiez-vous</a> pour être le premier à commenter ce partage</small>
            </h3>

            <?php endif; ?>
        </div>

        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <div class="col-md-10 shares-details-comments-editor-div">
                <textarea id="shares-details-comments-add-textarea" class="form-control" rows="3" ng-model="message"></textarea>
            </div>
            <div class="col-md-2 shares-details-comments-editor-div">
                <button id="shares-details-comments-add-btn" ng-click="onSendButtonClicked($event);" type="submit" class="btn btn-primary" data-loading-text="Sending...">
                    Envoyer
                </button>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php if ($this->LocalUser->isAuthenticated($this)) : ?>

<script>
    //On ready
    $(document).ready(function() {
        //Create editor
        new nicEditor({
            buttonList : ['bold', 'italic', 'underline', 'link', 'unlink']
        })
        .panelInstance('shares-details-comments-add-textarea');

        //Initial empty content
        var editor = nicEditors.findEditor('shares-details-comments-add-textarea');
        editor.setContent('');
    });
</script>

<?php endif; ?>