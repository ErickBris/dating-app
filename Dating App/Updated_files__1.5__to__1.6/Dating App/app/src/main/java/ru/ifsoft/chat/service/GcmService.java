package ru.ifsoft.chat.service;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.TaskStackBuilder;
import android.util.Log;

import com.google.android.gms.gcm.GcmListenerService;

import ru.ifsoft.chat.AppActivity;
import ru.ifsoft.chat.ChatFragment;
import ru.ifsoft.chat.DialogsActivity;
import ru.ifsoft.chat.FriendsActivity;
import ru.ifsoft.chat.MainActivity;
import ru.ifsoft.chat.NotificationsActivity;
import ru.ifsoft.chat.R;
import ru.ifsoft.chat.app.App;
import ru.ifsoft.chat.constants.Constants;


/**
 * Service used for receiving GCM messages. When a message is received this service will log it.
 */
public class GcmService extends GcmListenerService implements Constants {

    public GcmService() {

    }

    @Override
    public void onMessageReceived(String from, Bundle data) {

        generateNotification(getApplicationContext(), data);
        Log.e("Message", "Could not parse malformed JSON: \"" + data.toString() + "\"");
    }

    @Override
    public void onDeletedMessages() {
        sendNotification("Deleted messages on server");
    }

    @Override
    public void onMessageSent(String msgId) {
        sendNotification("Upstream message sent. Id=" + msgId);
    }

    @Override
    public void onSendError(String msgId, String error) {
        sendNotification("Upstream message send error. Id=" + msgId + ", error" + error);
    }

    // Put the message into a notification and post it.
    // This is just one simple example of what you might choose to do with
    // a GCM message.
    private void sendNotification(String msg) {

        Log.e("Message", "Could not parse malformed JSON: \"" + msg + "\"");
    }

    /**
     * Create a notification to inform the user that server has sent a message.
     */
    private static void generateNotification(Context context, Bundle data) {

        String message = data.getString("msg");
        String type = data.getString("type", "0");
        String actionId = data.getString("id", "0");
        String accountId = data.getString("accountId", "0");

        String msgId = data.getString("msgId", "0");
        String msgFromUserId = data.getString("msgFromUserId", "0");
        String msgFromUserState = data.getString("msgFromUserState", "0");
        String msgFromUserVerify = data.getString("msgFromUserVerify", "0");
        String msgFromUserUsername = data.getString("msgFromUserUsername", "");
        String msgFromUserFullname = data.getString("msgFromUserFullname", "");
        String msgFromUserPhotoUrl = data.getString("msgFromUserPhotoUrl", "");
        String msgMessage = data.getString("msgMessage", "");
        String msgImgUrl = data.getString("msgImgUrl", "");
        String msgCreateAt = data.getString("msgCreateAt", "0");
        String msgDate = data.getString("msgDate", "");
        String msgTimeAgo = data.getString("msgTimeAgo", "");
        String msgRemoveAt = data.getString("msgRemoveAt", "0");

        int icon = R.drawable.ic_action_push_notification;
        long when = System.currentTimeMillis();
        String title = context.getString(R.string.app_name);

        App.getInstance().reload();

        switch (Integer.valueOf(type)) {

            case GCM_NOTIFY_SYSTEM: {

                NotificationCompat.Builder mBuilder =
                        new NotificationCompat.Builder(context)
                                .setSmallIcon(R.drawable.ic_action_push_notification)
                                .setContentTitle(title)
                                .setContentText(message);

                Intent resultIntent;

                if (App.getInstance().getId() != 0) {

                    resultIntent = new Intent(context, MainActivity.class);

                } else {

                    resultIntent = new Intent(context, AppActivity.class);
                }

                resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                stackBuilder.addParentStack(MainActivity.class);
                stackBuilder.addNextIntent(resultIntent);
                PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                mBuilder.setContentIntent(resultPendingIntent);
                NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                mBuilder.setAutoCancel(true);
                mNotificationManager.notify(0, mBuilder.build());

                break;
            }

            case GCM_NOTIFY_CUSTOM: {

                if (App.getInstance().getId() != 0) {

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, MainActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(MainActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_PERSONAL: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, MainActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(MainActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_LIKE: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_profile_like);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, NotificationsActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(NotificationsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_FOLLOWER: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_friend_request);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, NotificationsActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(NotificationsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_COMMENT: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_comment);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, NotificationsActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(NotificationsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_MESSAGE: {

                if (App.getInstance().getId() != 0 && Long.valueOf(accountId) == App.getInstance().getId()) {

                    if (App.getInstance().getCurrentChatId() == Integer.valueOf(actionId)) {

                        Intent i = new Intent(ChatFragment.BROADCAST_ACTION);
                        i.putExtra(ChatFragment.PARAM_TASK, 0);
                        i.putExtra(ChatFragment.PARAM_STATUS, ChatFragment.STATUS_START);

                        i.putExtra("msgId", Integer.valueOf(msgId));
                        i.putExtra("msgFromUserId", Long.valueOf(msgFromUserId));
                        i.putExtra("msgFromUserState", Integer.valueOf(msgFromUserState));
                        i.putExtra("msgFromUserVerify", Integer.valueOf(msgFromUserVerify));
                        i.putExtra("msgFromUserUsername", String.valueOf(msgFromUserUsername));
                        i.putExtra("msgFromUserFullname", String.valueOf(msgFromUserFullname));
                        i.putExtra("msgFromUserPhotoUrl", String.valueOf(msgFromUserPhotoUrl));
                        i.putExtra("msgMessage", String.valueOf(msgMessage));
                        i.putExtra("msgImgUrl", String.valueOf(msgImgUrl));
                        i.putExtra("msgCreateAt", Integer.valueOf(msgCreateAt));
                        i.putExtra("msgDate", String.valueOf(msgDate));
                        i.putExtra("msgTimeAgo", String.valueOf(msgTimeAgo));

                        context.sendBroadcast(i);

                    } else {

                        if (App.getInstance().getAllowMessagesGCM() == ENABLED) {

                            message = context.getString(R.string.label_gcm_message);

                            NotificationCompat.Builder mBuilder =
                                    new NotificationCompat.Builder(context)
                                            .setSmallIcon(R.drawable.ic_action_push_notification)
                                            .setContentTitle(title)
                                            .setContentText(message);

                            Intent resultIntent = new Intent(context, DialogsActivity.class);
                            resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                            TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                            stackBuilder.addParentStack(DialogsActivity.class);
                            stackBuilder.addNextIntent(resultIntent);
                            PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                            mBuilder.setContentIntent(resultPendingIntent);
                            NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                            mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                            mBuilder.setAutoCancel(true);
                            mNotificationManager.notify(0, mBuilder.build());
                        }
                    }
                }

                break;
            }

            case GCM_NOTIFY_COMMENT_REPLY: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_comment_reply);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, NotificationsActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(NotificationsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_NOTIFY_GIFT: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_gift);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, NotificationsActivity.class);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(NotificationsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            case GCM_FRIEND_REQUEST_ACCEPTED: {

                if (App.getInstance().getId() != 0 && Long.toString(App.getInstance().getId()).equals(accountId)) {

                    message = context.getString(R.string.label_gcm_friend_request_accepted);

                    NotificationCompat.Builder mBuilder =
                            new NotificationCompat.Builder(context)
                                    .setSmallIcon(R.drawable.ic_action_push_notification)
                                    .setContentTitle(title)
                                    .setContentText(message);

                    Intent resultIntent = new Intent(context, FriendsActivity.class);
                    resultIntent.putExtra("gcm", true);
                    resultIntent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_SINGLE_TOP);

                    TaskStackBuilder stackBuilder = TaskStackBuilder.create(context);
                    stackBuilder.addParentStack(FriendsActivity.class);
                    stackBuilder.addNextIntent(resultIntent);
                    PendingIntent resultPendingIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);
                    mBuilder.setContentIntent(resultPendingIntent);
                    NotificationManager mNotificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
                    mBuilder.setDefaults(Notification.DEFAULT_SOUND | Notification.DEFAULT_VIBRATE);
                    mBuilder.setAutoCancel(true);
                    mNotificationManager.notify(0, mBuilder.build());
                }

                break;
            }

            default: {

                break;
            }
        }
    }
}
