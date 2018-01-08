<main>
    <br/>
    <br/>
    <br/>
    <form action="index.php?c=User&a=SessionCancel" method="post">
        <table>
            <thead>
            <tr>
                <th>Date de connection</th>
                <th>Date de dernier usage</th>
                <th>IP de dernier usage</th>
                <th>Navigateur</th>
                <th>Annuler</th>
            </tr>
            </thead>
            <tbody>
            <?php
            function formatRow(\Entities\Session $session, \Entities\Request $last_request): string
            {
                $session_creation = date(\DATE_RFC1123, $session->getStarted());
                $last_usage = date(\DATE_RFC1123, $last_request->getStartedProcessing());
                $last_ip = $last_request->getIP();
                $last_user_agent = htmlspecialchars($last_request->getUserAgentTxt());

                $row = '<tr>
        <td>' . $session_creation . '</td>
        <td>' . $last_usage . '</td>
        <td>' . $last_ip . '</td>
        <td>' . $last_user_agent . '</td>
        <td><input type="checkbox" name="session_id[]" value="' . $session->getID() . '"/></td>
    </tr>';
                return $row;
            }

            foreach ($data["sessions"] as $session) {
                echo formatRow($session, $data["requests"][$session->getID()]);
            }
            ?>
            </tbody>
        </table>
        <input type="submit" value="Annuler le ou les sessions selectionnées"/>
    </form>
</main>